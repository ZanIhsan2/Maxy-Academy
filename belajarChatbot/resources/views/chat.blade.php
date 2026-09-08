<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Chat | Groq AI</title>
    <style>
        :root {
            --ink: #1d2925;
            --muted: #708078;
            --line: #dbe5df;
            --paper: #f6f8f3;
            --white: #fff;
            --accent: #1e6b52;
            --accent-soft: #e1f0e8;
            --user: #e7f3ec;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background: var(--paper);
            color: var(--ink);
            font-family: Georgia, 'Times New Roman', serif;
        }

        button,
        textarea {
            font: inherit;
        }

        .app {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        .sidebar {
            padding: 28px 20px;
            background: #183b30;
            color: #eaf4ee;
        }

        .brand {
            margin: 0 0 8px;
            font-size: 25px;
            letter-spacing: -.5px;
        }

        .tagline {
            margin: 0 0 28px;
            color: #a9c4b5;
            font: 13px/1.5 Arial, sans-serif;
        }

        .new-chat {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #70a88e;
            border-radius: 6px;
            background: transparent;
            color: #fff;
            cursor: pointer;
            text-align: left;
        }

        .new-chat:hover {
            background: #285744;
        }

        .section-label {
            margin: 32px 0 10px;
            color: #9abbab;
            font: 11px Arial, sans-serif;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .sessions {
            display: grid;
            gap: 5px;
        }

        .session {
            overflow: hidden;
            padding: 10px 11px;
            border: 0;
            border-radius: 5px;
            background: transparent;
            color: #dcebe3;
            cursor: pointer;
            text-align: left;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .session:hover,
        .session.active {
            background: #285744;
        }

        .main {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 25px clamp(20px, 6vw, 88px);
            border-bottom: 1px solid var(--line);
            background: rgba(255, 255, 255, .7);
        }

        .topbar h1 {
            margin: 0;
            font-size: clamp(24px, 3vw, 38px);
            font-weight: 400;
        }

        .status {
            color: var(--accent);
            font: 12px Arial, sans-serif;
        }

        .status::before {
            display: inline-block;
            width: 7px;
            height: 7px;
            margin-right: 7px;
            border-radius: 50%;
            background: #4cb782;
            content: '';
        }

        .messages {
            width: min(860px, 100%);
            flex: 1;
            margin: 0 auto;
            padding: 42px clamp(20px, 6vw, 60px) 28px;
        }

        .welcome {
            max-width: 610px;
            margin: 12vh auto 0;
            text-align: center;
        }

        .welcome-mark {
            display: inline-grid;
            width: 56px;
            height: 56px;
            place-items: center;
            border-radius: 50%;
            background: var(--accent-soft);
            color: var(--accent);
            font-size: 27px;
        }

        .welcome h2 {
            margin: 20px 0 8px;
            font-size: 31px;
            font-weight: 400;
        }

        .welcome p {
            margin: 0;
            color: var(--muted);
            font: 15px/1.6 Arial, sans-serif;
        }

        .message {
            display: flex;
            gap: 14px;
            margin-bottom: 26px;
            animation: appear .25s ease-out;
        }

        .message.user {
            justify-content: flex-end;
        }

        .bubble {
            max-width: 78%;
            padding: 13px 16px;
            border-radius: 8px;
            font-size: 16px;
            line-height: 1.55;
            white-space: pre-wrap;
        }

        .assistant .bubble {
            border: 1px solid var(--line);
            background: var(--white);
        }

        .user .bubble {
            background: var(--user);
        }

        .composer-wrap {
            padding: 0 clamp(20px, 6vw, 88px) 28px;
        }

        .composer {
            display: flex;
            width: min(860px, 100%);
            margin: 0 auto;
            padding: 8px;
            border: 1px solid #b9cbbf;
            border-radius: 8px;
            background: var(--white);
            box-shadow: 0 7px 22px rgba(24, 59, 48, .07);
        }

        textarea {
            min-height: 48px;
            max-height: 140px;
            flex: 1;
            resize: none;
            padding: 12px;
            border: 0;
            outline: 0;
            color: var(--ink);
            line-height: 1.4;
        }

        .send {
            align-self: flex-end;
            width: 44px;
            height: 44px;
            border: 0;
            border-radius: 5px;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            font-size: 20px;
        }

        .send:disabled {
            cursor: wait;
            opacity: .55;
        }

        .hint {
            margin: 10px 0 0;
            color: #89968e;
            font: 11px Arial, sans-serif;
            text-align: center;
        }

        @keyframes appear {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 700px) {
            .app {
                grid-template-columns: 1fr;
            }

            .sidebar {
                padding: 18px 20px;
            }

            .sidebar .section-label,
            .sessions {
                display: none;
            }

            .brand {
                margin-bottom: 3px;
            }

            .tagline {
                margin-bottom: 14px;
            }

            .new-chat {
                padding: 9px 12px;
            }

            .topbar {
                padding: 20px;
            }

            .messages {
                padding-top: 28px;
            }

            .bubble {
                max-width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="app">
        <aside class="sidebar">
            <h1 class="brand">Ruang Chat</h1>
            <p class="tagline">Teman berpikir yang cepat dan sederhana.</p>
            <button class="new-chat" id="newChat" type="button">+ Percakapan baru</button>
            <p class="section-label">Percakapan terakhir</p>
            <div class="sessions" id="sessions">
                @foreach (($session ?? collect()) as $item)
                <button class="session" type="button" data-session-id="{{ $item->id }}">{{ $item->name }}</button>
                @endforeach
            </div>
        </aside>
        <main class="main">
            <header class="topbar">
                <h1 id="chatTitle">Percakapan baru</h1><span class="status">Groq AI aktif</span>
            </header>
            <section class="messages" id="messages" aria-live="polite">
                <div class="welcome" id="welcome"><span class="welcome-mark">*</span>
                    <h2>Apa yang sedang Anda pikirkan?</h2>
                    <p>Tulis pertanyaan, ide, atau tugas di bawah. Saya akan membantu menyusunnya.</p>
                </div>
            </section>
            <div class="composer-wrap">
                <form class="composer" id="chatForm"><textarea id="message" rows="1" placeholder="Ketik pesan Anda..." aria-label="Pesan"></textarea><button class="send" id="sendButton" type="submit" aria-label="Kirim pesan">^</button></form>
                <p class="hint">Enter untuk mengirim - Shift + Enter untuk baris baru</p>
            </div>
        </main>
    </div>
    <script>
        const messages = document.getElementById('messages');
        const welcome = document.getElementById('welcome');
        const form = document.getElementById('chatForm');
        const input = document.getElementById('message');
        const sendButton = document.getElementById('sendButton');
        let sessionId = null;

        function addMessage(text, role) {
            if (welcome) welcome.remove();
            const item = document.createElement('div');
            item.className = `message ${role}`;
            const bubble = document.createElement('div');
            bubble.className = 'bubble';
            bubble.textContent = text;
            item.appendChild(bubble);
            messages.appendChild(item);
            messages.scrollTop = messages.scrollHeight;
        }

        function setLoading(loading) {
            sendButton.disabled = loading;
            sendButton.textContent = loading ? '...' : '^';
        }
        async function loadSession(id, button) {
            const response = await fetch(`/chat/session/${id}`);
            const chats = await response.json();
            messages.innerHTML = '';
            chats.reverse().forEach(chat => {
                addMessage(chat.question, 'user');
                addMessage(chat.answer, 'assistant');
            });
            sessionId = id;
            document.getElementById('chatTitle').textContent = button.textContent;
            document.querySelectorAll('.session').forEach(item => item.classList.remove('active'));
            button.classList.add('active');
        }
        document.querySelectorAll('.session').forEach(button => button.addEventListener('click', () => loadSession(button.dataset.sessionId, button)));
        document.getElementById('newChat').addEventListener('click', () => {
            sessionId = null;
            messages.innerHTML = '';
            document.getElementById('chatTitle').textContent = 'Percakapan baru';
            input.focus();
        });
        input.addEventListener('keydown', event => {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                form.requestSubmit();
            }
        });
        form.addEventListener('submit', async event => {
            event.preventDefault();
            const message = input.value.trim();
            if (!message || sendButton.disabled) return;
            addMessage(message, 'user');
            input.value = '';
            setLoading(true);
            try {
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message,
                        session_id: sessionId
                    })
                });
                const data = await response.json();
                if (!response.ok) throw new Error(data.error ? `${data.message} (${data.status}): ${data.error}` : (data.message || 'Terjadi kesalahan.'));
                sessionId = data.session_id;
                addMessage(data.chat.answer, 'assistant');
            } catch (error) {
                addMessage(error.message, 'assistant');
            } finally {
                setLoading(false);
                input.focus();
            }
        });
    </script>
</body>

</html>