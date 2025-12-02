// AI Chat Widget
class ChatWidget {
    constructor() {
        this.isOpen = false;
        this.messages = [];
        this.init();
    }

    init() {
        this.createWidget();
        this.attachEventListeners();
    }

    createWidget() {
        const widgetHTML = `
            <div id="chat-widget" class="chat-widget">
                <button id="chat-toggle" class="chat-toggle">
                    <i class="fas fa-comments"></i>
                </button>
                <div id="chat-box" class="chat-box" style="display: none;">
                    <div class="chat-header">
                        <h5><i class="fas fa-robot"></i> Gaming Assistant</h5>
                        <button id="chat-close" class="chat-close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="chat-messages" class="chat-messages">
                        <div class="bot-message">
                            <strong>AI Assistant:</strong> Hi! I'm your gaming assistant. Ask me anything about games!
                        </div>
                    </div>
                    <div class="chat-input-area">
                        <input type="text" id="chat-input" class="chat-input" placeholder="Ask about games..." maxlength="1000">
                        <button id="chat-send" class="chat-send-btn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', widgetHTML);
    }

    attachEventListeners() {
        const toggleBtn = document.getElementById('chat-toggle');
        const closeBtn = document.getElementById('chat-close');
        const sendBtn = document.getElementById('chat-send');
        const input = document.getElementById('chat-input');

        toggleBtn.addEventListener('click', () => this.toggleChat());
        closeBtn.addEventListener('click', () => this.closeChat());
        sendBtn.addEventListener('click', () => this.sendMessage());
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') this.sendMessage();
        });
    }

    toggleChat() {
        const chatBox = document.getElementById('chat-box');
        this.isOpen = !this.isOpen;
        chatBox.style.display = this.isOpen ? 'flex' : 'none';
    }

    closeChat() {
        const chatBox = document.getElementById('chat-box');
        this.isOpen = false;
        chatBox.style.display = 'none';
    }

    async sendMessage() {
        const input = document.getElementById('chat-input');
        const message = input.value.trim();
        
        if (!message) return;

        // Add user message to chat and history
        this.addMessage(message, 'user');
        this.messages.push({ role: 'user', content: message });
        input.value = '';

        // Show typing indicator
        this.showTyping();

        try {
            const response = await fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ 
                    message,
                    history: this.messages
                })
            });

            const data = await response.json();
            this.removeTyping();

            if (data.success) {
                this.addMessage(data.reply, 'bot');
                this.messages.push({ role: 'bot', content: data.reply });
            } else {
                this.addMessage('Sorry, I encountered an error. Please try again.', 'bot');
            }
        } catch (error) {
            this.removeTyping();
            this.addMessage('Sorry, I could not connect. Please check your internet connection.', 'bot');
        }
    }

    addMessage(text, sender) {
        const messagesDiv = document.getElementById('chat-messages');
        const messageClass = sender === 'user' ? 'user-message' : 'bot-message';
        const label = sender === 'user' ? 'You' : 'AI Assistant';
        
        const messageHTML = `
            <div class="${messageClass}">
                <strong>${label}:</strong> ${this.escapeHtml(text)}
            </div>
        `;
        
        messagesDiv.insertAdjacentHTML('beforeend', messageHTML);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    showTyping() {
        const messagesDiv = document.getElementById('chat-messages');
        const typingHTML = `
            <div class="bot-message typing-indicator" id="typing">
                <strong>AI Assistant:</strong> <span class="dots"><span>.</span><span>.</span><span>.</span></span>
            </div>
        `;
        messagesDiv.insertAdjacentHTML('beforeend', typingHTML);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    removeTyping() {
        const typing = document.getElementById('typing');
        if (typing) typing.remove();
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize chat widget when page loads
document.addEventListener('DOMContentLoaded', () => {
    new ChatWidget();
});
