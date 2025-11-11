@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b px-6 py-4">
            <h1 class="text-xl font-semibold text-gray-800">Chat</h1>
        </div>
        
        <div class="flex h-screen">
            <!-- Daftar Chat -->
            <div class="w-1/3 border-r overflow-y-auto">
                <div class="p-4">
                    <div class="relative">
                        <input type="text" placeholder="Cari pengguna..." class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                
                <div id="chat-list" class="divide-y">
                    <!-- Daftar chat akan dimuat dengan JavaScript -->
                </div>
            </div>
            
            <!-- Area Chat -->
            <div class="flex-1 flex flex-col">
                <div id="chat-header" class="border-b px-6 py-4 bg-gray-50">
                    <p class="text-gray-500 text-center">Pilih chat untuk memulai percakapan</p>
                </div>
                
                <div id="chat-messages" class="flex-1 overflow-y-auto p-6">
                    <!-- Pesan akan dimuat dengan JavaScript -->
                </div>
                
                <div id="chat-input" class="border-t p-4 hidden">
                    <form id="message-form" class="flex items-center">
                        <input type="text" id="message-input" placeholder="Ketik pesan..." class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                        <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentChatId = null;
    let currentUserId = null;
    
    // Load daftar chat
    loadChatList();
    
    // Load online users
    loadOnlineUsers();
    
    // Submit form pesan
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });
    
    function loadChatList() {
        fetch('{{ route("chat.index") }}')
            .then(response => response.json())
            .then(data => {
                const chatList = document.getElementById('chat-list');
                chatList.innerHTML = '';
                
                if (data.chats && data.chats.length > 0) {
                    data.chats.forEach(chat => {
                        const otherUser = chat.user1_id === {{ Auth::id() }} ? chat.user2 : chat.user1;
                        const chatItem = document.createElement('div');
                        chatItem.className = 'p-4 hover:bg-gray-100 cursor-pointer';
                        chatItem.innerHTML = `
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-gray-600 font-bold">${otherUser.initials || 'U'}</span>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-semibold">${otherUser.nama_lengkap}</h3>
                                    <p class="text-sm text-gray-600 truncate">${chat.last_message ? chat.last_message.message : 'Belum ada pesan'}</p>
                                </div>
                                <div class="text-xs text-gray-500">
                                    ${chat.last_message_at ? new Date(chat.last_message_at).toLocaleTimeString() : ''}
                                </div>
                            </div>
                        `;
                        chatItem.addEventListener('click', () => openChat(otherUser.id));
                        chatList.appendChild(chatItem);
                    });
                } else {
                    chatList.innerHTML = '<div class="p-4 text-center text-gray-500">Belum ada chat</div>';
                }
            })
            .catch(error => console.error('Error loading chat list:', error));
    }
    
    function loadOnlineUsers() {
        fetch('{{ route("chat.online-users") }}')
            .then(response => response.json())
            .then(users => {
                // Tambahkan user online ke daftar chat
                const onlineUsersList = document.createElement('div');
                onlineUsersList.className = 'p-4 bg-blue-50 border-b';
                onlineUsersList.innerHTML = '<h3 class="font-semibold text-blue-800 mb-2">Pengguna Online</h3>';
                
                users.forEach(user => {
                    const userItem = document.createElement('div');
                    userItem.className = 'flex items-center p-2 hover:bg-blue-100 cursor-pointer rounded';
                    userItem.innerHTML = `
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mr-2">
                            <span class="text-white text-xs font-bold">${user.initials || 'U'}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium">${user.nama_lengkap}</p>
                            <p class="text-xs text-gray-500">${user.jabatan} • ${user.last_activity_formatted}</p>
                        </div>
                    `;
                    userItem.addEventListener('click', () => openChat(user.id));
                    onlineUsersList.appendChild(userItem);
                });
                
                const chatList = document.getElementById('chat-list');
                chatList.insertBefore(onlineUsersList, chatList.firstChild);
            })
            .catch(error => console.error('Error loading online users:', error));
    }
    
    function openChat(userId) {
        currentUserId = userId;
        
        // Update header
        document.getElementById('chat-header').innerHTML = '<div class="flex items-center"><div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center mr-3"><span class="text-gray-600 font-bold">U</span></div><p class="font-semibold">Memuat...</p></div>';
        
        // Tampilkan input pesan
        document.getElementById('chat-input').classList.remove('hidden');
        
        // Load chat
        fetch(`{{ route("chat.get", ":userId") }}`.replace(':userId', userId))
            .then(response => response.json())
            .then(data => {
                currentChatId = data.chat.id;
                
                // Update header dengan nama user
                const otherUser = data.chat.user1_id === {{ Auth::id() }} ? data.chat.user2 : data.chat.user1;
                document.getElementById('chat-header').innerHTML = `
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center mr-3">
                            <span class="text-gray-600 font-bold">${otherUser.initials || 'U'}</span>
                        </div>
                        <div>
                            <p class="font-semibold">${otherUser.nama_lengkap}</p>
                            <p class="text-sm text-gray-500">${otherUser.jabatan}</p>
                        </div>
                    </div>
                `;
                
                // Load pesan
                loadMessages(data.messages);
            })
            .catch(error => console.error('Error loading chat:', error));
    }
    
    function loadMessages(messages) {
        const messagesContainer = document.getElementById('chat-messages');
        messagesContainer.innerHTML = '';
        
        if (messages && messages.length > 0) {
            messages.forEach(message => {
                const messageElement = document.createElement('div');
                const isOwnMessage = message.sender_id === {{ Auth::id() }};
                
                messageElement.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'} mb-4`;
                messageElement.innerHTML = `
                    <div class="max-w-xs lg:max-w-md">
                        <div class="${isOwnMessage ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'} rounded-lg px-4 py-2">
                            <p>${message.message}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 ${isOwnMessage ? 'text-right' : ''}">${new Date(message.created_at).toLocaleTimeString()}</p>
                    </div>
                `;
                
                messagesContainer.appendChild(messageElement);
            });
            
            // Scroll ke bawah
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        } else {
            messagesContainer.innerHTML = '<div class="text-center text-gray-500 py-8">Belum ada pesan. Mulai percakapan dengan mengirim pesan.</div>';
        }
    }
    
    function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();
        
        if (!message || !currentUserId) return;
        
        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                receiver_id: currentUserId,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageInput.value = '';
                
                // Tambahkan pesan ke daftar
                const messagesContainer = document.getElementById('chat-messages');
                const messageElement = document.createElement('div');
                messageElement.className = 'flex justify-end mb-4';
                messageElement.innerHTML = `
                    <div class="max-w-xs lg:max-w-md">
                        <div class="bg-blue-500 text-white rounded-lg px-4 py-2">
                            <p>${data.message.message}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-right">${new Date().toLocaleTimeString()}</p>
                    </div>
                `;
                
                messagesContainer.appendChild(messageElement);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        })
        .catch(error => console.error('Error sending message:', error));
    }
});
</script>
@endsection