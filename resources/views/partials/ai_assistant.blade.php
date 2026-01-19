<div id="aiChat" class="ai-chat" style="display:none;">
    <div class="ai-header">
        <span>🤖 AI Assistant</span>

        <div class="ai-controls">
            <button class="ai-btn" onclick="toggleMinimize()">➖</button>
            <button class="ai-btn" onclick="closeAI()">✖</button>
        </div>
    </div>

    <div id="aiMessages" class="ai-messages">
        <div><b>AI:</b> Ask me about sales, inventory, employees 🙂</div>
    </div>

    <form id="aiForm" class="ai-form">
        <input id="aiInput" class="ai-input" placeholder="Type your question...">
        <button class="ai-send">Send</button>
    </form>
</div>

<div id="aiReopen" class="ai-reopen" style="display:none;" onclick="openAI()">
    🤖
</div>
<script>
    const aiChat = document.getElementById('aiChat');
    const aiReopen = document.getElementById('aiReopen');

    // 🔥 SHOW ONLY AFTER FULL PAGE LOAD
    window.addEventListener('load', () => {
        aiChat.style.display = 'block';
        aiReopen.style.display = 'none';
    });

    function toggleMinimize() {
        aiChat.classList.toggle('ai-minimized');
    }

    function closeAI() {
        aiChat.style.display = 'none';
        aiReopen.style.display = 'flex';
    }

    function openAI() {
        aiChat.style.display = 'block';
        aiChat.classList.remove('ai-minimized');
        aiReopen.style.display = 'none';
    }

    const aiForm = document.getElementById('aiForm');
    const aiInput = document.getElementById('aiInput');
    const aiMessages = document.getElementById('aiMessages');

    aiForm.onsubmit = async (e) => {
        e.preventDefault();
        const q = aiInput.value.trim();
        if (!q) return;

        aiMessages.innerHTML += `<div><b>You:</b> ${q}</div>`;
        aiInput.value = '';

        const res = await fetch('/ai/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                question: q
            })
        });

        const data = await res.json();
        aiMessages.innerHTML += `<div><b>AI:</b> ${data.answer}</div>`;
        aiMessages.scrollTop = aiMessages.scrollHeight;
    };
</script>
