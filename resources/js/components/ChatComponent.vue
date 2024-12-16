<template>
    <div class="flex flex-col h-[500px]">
        <div class="flex items-center">
            <h1 class="text-lg font-semibold mr-2">{{ user.name }}</h1>
            <span :class="isUserOnline ? 'bg-green-500' : 'bg-gray-400'" class="inline-block h-2 w-2 rounded-full"></span>
        </div>

        <!-- Messages -->
        <div ref="messageContainer" class="overflow-y-auto p-4 mt-3 flex-grow border-t border-gray-200">
            <div class="space-y-4">
                <div
                    v-for="message in messages"
                    :key="message.id"
                    :class="{ 'text-right': message.sender_id === currentUser.id }"
                    class="mb-4"
                >
                    <div
                        :class="message.sender_id === currentUser.id ? 'bg-red-500/20 text-black' : 'bg-gray-200 text-gray-800'"
                        class="inline-block px-5 py-2 rounded-lg"
                    >
                        <p>{{ message.text }}</p>
                        <span class="text-[10px]">{{ formatTime(message.created_at) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="border-t pt-4">
            <form @submit.prevent="sendMessage">
                <div class="flex items-center">
                    <input
                        v-model="newMessage"
                        @keydown="sendTypingEvent"
                        type="text"
                        class="flex-1 border p-3 rounded-lg"
                        placeholder="Type your message here..."
                    />
                    <button type="submit" class="ml-2 bg-indigo-500 text-black p-3 rounded-lg shadow hover:bg-indigo-600 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-paper-plane"></i>
                        <span class="ml-2">Send</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Typing Indicator -->
        <small v-if="isUserTyping" class="text-gray-600 mt-5">
            {{ typingUserName }} is typing...
        </small>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue';
import axios from 'axios';

// Define props
const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    currentUser: {
        type: Object,
        required: true,
    },
});

// Reactive state variables
const messages = ref([]);
const newMessage = ref('');
const messageContainer = ref(null);
const isUserTyping = ref(false);
const isUserTypingTimer = ref(null);
const isUserOnline = ref(false);
const typingUserName = ref('');

// Automatically scroll to the bottom when messages are updated
watch(
    messages,
    () => {
        nextTick(() => {
            messageContainer.value.scrollTo({
                top: messageContainer.value.scrollHeight,
                behavior: 'smooth',
            });
        });
    },
    { deep: true }
);

// Fetch messages from the server
const fetchMessages = async () => {
    try {
        const response = await axios.get(`/messages/${props.user.id}`);
        messages.value = response.data;
    } catch (error) {
        console.error('Failed to fetch messages:', error);
    }
};

// Send a new message to the server
const sendMessage = async () => {
    if (newMessage.value.trim() !== '') {
        try {
            const response = await axios.post(`/messages/${props.user.id}`, {
                message: newMessage.value,
            });
            messages.value.push(response.data);
            newMessage.value = '';
        } catch (error) {
            console.error('Failed to send message:', error);
        }
    }
};

// Broadcast a typing event to the other user
const sendTypingEvent = () => {
    console.log('Sending typing event on channel:', `chat.${props.user.id}`);

    Echo.private(`chat.${props.user.id}`).whisper('typing', {
        // Send the user ID and name
        userID: props.currentUser.id,
        userName: props.currentUser.name,
    });
};

// Format timestamps for display
const formatTime = (datetime) => {
    const options = { hour: '2-digit', minute: '2-digit' };
    return new Date(datetime).toLocaleTimeString([], options);
};

// Initialize the component
onMounted(() => {
    fetchMessages();

    // Manage online status
    Echo.join(`presence.chat.${props.user.id}`)
        .here((users) => {
            isUserOnline.value = users.some((user) => user.id === props.user.id);
        })
        .joining((user) => {
            if (user.id === props.user.id) isUserOnline.value = true;
        })
        .leaving((user) => {
            if (user.id === props.user.id) isUserOnline.value = false;
        });

    // Listen for new messages and typing events
    Echo.private(`chat.${props.user.id}`)
        .listen('MessageSent', (response) => {
            console.log('Message received:', response); // Debugging log
            messages.value.push(response.message);
        })
        .listenForWhisper('typing', (response) => {
            console.log('Typing event received:', response);
            if (response.userID === props.user.id) {
                isUserTyping.value = true;
                typingUserName.value = response.userName;

                if (isUserTypingTimer.value) {
                    clearTimeout(isUserTypingTimer.value);
                }

                isUserTypingTimer.value = setTimeout(() => {
                    isUserTyping.value = false;
                    typingUserName.value = '';
                }, 1000);
            }
        });
});
</script>

<style scoped>
/* Add custom styles here */
</style>
