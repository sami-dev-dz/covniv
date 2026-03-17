<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/main.css">
    <title>CovNiv - Messagerie</title>
    <style>
        .messaging-container {
            display: flex;
            height: calc(100vh - 140px);
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .conversations-sidebar {
            width: 320px;
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            background-color: var(--bg-surface);
            transition: transform 0.3s ease;
        }

        .search-bar {
            padding: var(--spacing-4);
            border-bottom: 1px solid var(--border-color);
        }

        .conversation-list {
            flex: 1;
            overflow-y: auto;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .conversation-item {
            display: flex;
            align-items: center;
            padding: var(--spacing-4);
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: background-color 0.2s ease;
            position: relative;
        }

        .conversation-item:hover {
            background-color: var(--bg-surface-hover);
        }

        .conversation-item.active {
            background-color: var(--primary-50);
            border-left: 4px solid var(--primary-600);
        }

        .conversation-info {
            flex: 1;
            margin-left: var(--spacing-3);
            overflow: hidden;
        }

        .message-time {
            font-size: 0.75rem;
            color: var(--text-muted);
            position: absolute;
            top: var(--spacing-4);
            right: var(--spacing-4);
        }

        .chat-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-base);
        }

        .chat-header {
            padding: var(--spacing-4) var(--spacing-6);
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: var(--spacing-4);
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: var(--spacing-6);
            display: flex;
            flex-direction: column;
            gap: var(--spacing-4);
        }

        .message {
            max-width: 70%;
            padding: var(--spacing-3) var(--spacing-4);
            border-radius: var(--radius-lg);
            position: relative;
            word-wrap: break-word;
        }

        .message.sent {
            align-self: flex-end;
            background-color: var(--primary-600);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .message.received {
            align-self: flex-start;
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-bottom-left-radius: 4px;
        }

        .message .time {
            font-size: 0.7rem;
            opacity: 0.8;
            margin-top: 4px;
            display: block;
            text-align: right;
        }

        .message-input-container {
            padding: var(--spacing-4);
            background-color: var(--bg-surface);
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: var(--spacing-3);
            align-items: flex-end;
        }

        .message-input {
            flex: 1;
            resize: none;
            padding: var(--spacing-3);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background-color: var(--bg-base);
            font-family: inherit;
            max-height: 120px;
        }

        .message-input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 2px var(--primary-100);
        }

        #mobile-menu-toggle, #back-to-conversations, #close-sidebar {
            display: none;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: var(--spacing-2);
        }

        @media (max-width: 768px) {
            .messaging-container {
                position: relative;
                height: calc(100vh - 160px);
            }
            .conversations-sidebar {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                z-index: 10;
                transform: translateX(-100%);
            }
            .conversations-sidebar.active {
                transform: translateX(0);
            }
            #mobile-menu-toggle, #close-sidebar {
                display: block;
            }
            .chat-container {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container navbar-container">
            <a href="/principal" class="navbar-brand">
                <i data-lucide="car-front"></i>
                CovNiv
            </a>
            
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i data-lucide="menu"></i>
            </button>

            <div class="navbar-nav" id="navbarNav">
                <div class="nav-actions">
                    <button class="btn-icon" id="themeToggle" title="Basculer le thème">
                        <i data-lucide="moon"></i>
                    </button>
                    <a href="/logout" class="btn btn-outline" style="color: var(--danger-600); border-color: var(--danger-50); background-color: var(--danger-50);">
                        <i data-lucide="log-out"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content container pr-0 pl-0 md:pr-4 md:pl-4">
        <div class="dashboard-container">
            <!-- Sidebar -->
            <aside class="sidebar hidden md:block">
                <nav class="sidebar-nav">
                    <a href="/principal">
                        <i data-lucide="home"></i> Tableau de bord
                    </a>
                    <a href="/profil">
                        <i data-lucide="user"></i> Profil
                    </a>
                    <a href="/messagerie" class="active">
                        <i data-lucide="message-square"></i> Messagerie
                    </a>
                    <a href="/demandes-recues">
                        <i data-lucide="bell"></i> Demandes reçues
                    </a>
                    <a href="/historique">
                        <i data-lucide="calendar"></i> Historique
                    </a>
                </nav>
            </aside>

            <!-- Content -->
            <div class="dashboard-content" style="padding: 0; background: transparent; border: none; box-shadow: none;">
                <div class="messaging-container">
                    
                    <!-- Liste des conversations -->
                    <div class="conversations-sidebar" <?= !$dest_id ? 'style="transform: translateX(0);"' : '' ?>>
                        <div class="flex items-center justify-between p-4 border-b border-border">
                            <h2 class="font-bold text-lg">Conversations</h2>
                            <button id="close-sidebar"><i data-lucide="x"></i></button>
                        </div>
                        
                        <div class="search-bar">
                            <form method="GET" action="/messagerie" class="w-full">
                                <div class="input-group">
                                    <div class="input-icon"><i data-lucide="search"></i></div>
                                    <select id="contact-select" name="destinataire_id" class="form-control" onchange="this.form.submit()" style="padding-left: 2.5rem; border-radius: 999px;">
                                        <option value="">Nouvelle discussion...</option>
                                        <?php foreach ($utilisateurs as $u): ?>
                                            <option value="<?= $u['user_id'] ?>" <?= ($dest_id == $u['user_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if (isset($trajet_id) && $trajet_id): ?>
                                    <input type="hidden" name="trajet_id" value="<?= htmlspecialchars($trajet_id) ?>">
                                <?php endif; ?>
                            </form>
                        </div>
                        
                        <ul class="conversation-list">
                            <?php if (!empty($boite_reception)): ?>
                                <?php foreach ($boite_reception as $msg): ?>
                                    <li class="conversation-item <?= ($dest_id == $msg['expediteur_id']) ? 'active' : '' ?>" 
                                        onclick="location.href='/messagerie?destinataire_id=<?= $msg['expediteur_id'] ?><?= isset($msg['trajet_id']) && $msg['trajet_id'] ? '&trajet_id='.$msg['trajet_id'] : '' ?>'">
                                        
                                        <div style="width: 48px; height: 48px; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0;">
                                            <?php if (!empty($msg['photo_profil']) && file_exists(BASE_PATH . 'public/' . $msg['photo_profil'])): ?>
                                                <img src="/<?= htmlspecialchars($msg['photo_profil']); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <i data-lucide="user"></i>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="conversation-info">
                                            <div class="font-bold text-sm mb-1"><?= htmlspecialchars($msg['prenom'] . ' ' . $msg['nom']) ?></div>
                                            <div class="text-xs text-muted truncate"><?= htmlspecialchars($msg['contenu_message']) ?></div>
                                        </div>
                                        
                                        <div class="message-time"><?= date('H:i', strtotime($msg['date_envoi'])) ?></div>
                                        
                                        <?php if (isset($msg['unread_count']) && $msg['unread_count'] > 0): ?>
                                            <div class="badge badge-primary" style="position: absolute; bottom: var(--spacing-4); right: var(--spacing-4); font-size: 0.65rem; padding: 2px 6px; border-radius: 999px;">
                                                <?= $msg['unread_count'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="p-8 text-center text-muted">
                                    <i data-lucide="message-square" style="width: 32px; height: 32px; margin: 0 auto 16px; opacity: 0.5;"></i>
                                    <p class="text-sm">Aucune conversation récente.</p>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Chat Box -->
                    <div class="chat-container">
                        <!-- Header -->
                        <div class="chat-header">
                            <button id="mobile-menu-toggle"><i data-lucide="menu"></i></button>
                            
                            <?php if ($dest_id): ?>
                                <div class="flex items-center gap-3">
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-100); color: var(--primary-600); display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                        <?php if (!empty($current_avatar) && file_exists(BASE_PATH . 'public/' . $current_avatar)): ?>
                                            <img src="/<?= htmlspecialchars($current_avatar); ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <i data-lucide="user"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="font-bold"><?= htmlspecialchars($current_conversation_name) ?></div>
                                        <?php if (isset($trajet_id) && $trajet_id): ?>
                                            <div class="text-xs text-primary-600 bg-primary-50 px-2 py-1 rounded inline-block mt-1">
                                                Trajet #<?= htmlspecialchars($trajet_id) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="font-bold text-muted">Sélectionnez une conversation</div>
                            <?php endif; ?>
                        </div>

                        <!-- Messages -->
                        <div class="messages-container" id="messages-container">
                            <?php if ($dest_id && !empty($messages)): ?>
                                <?php foreach ($messages as $m): ?>
                                    <div class="message <?= $m['expediteur_id'] == $mon_id ? 'sent' : 'received' ?>">
                                        <?= nl2br(htmlspecialchars($m['contenu_message'])) ?>
                                        <span class="time">
                                            <?= date('H:i', strtotime($m['date_envoi'])) ?>
                                            <?php if ($m['expediteur_id'] == $mon_id): ?>
                                                <i data-lucide="check" style="width: 10px; height: 10px; display: inline; margin-left: 2px;"></i>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                <?php endforeach; ?>
                            <?php elseif ($dest_id): ?>
                                <div class="text-center text-muted mt-8">
                                    <p>Envoyez un message pour commencer la discussion.</p>
                                </div>
                            <?php else: ?>
                                <div class="h-full flex flex-col items-center justify-center text-muted">
                                    <div style="width: 80px; height: 80px; background-color: var(--bg-surface-hover); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: var(--spacing-6);">
                                        <i data-lucide="messages-square" style="width: 40px; height: 40px; opacity: 0.5;"></i>
                                    </div>
                                    <h3 class="font-bold text-lg mb-2">Vos messages</h3>
                                    <p>Sélectionnez un contact pour afficher les messages.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Input -->
                        <form id="message-form" method="POST" action="/envoyer-message" class="message-input-container">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(\App\Services\AuthSecurity::generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
                            <?php if ($dest_id): ?>
                                <input type="hidden" name="destinataire_id" value="<?= $dest_id ?>">
                                <?php if (isset($trajet_id) && $trajet_id): ?>
                                    <input type="hidden" name="trajet_id" value="<?= htmlspecialchars($trajet_id) ?>">
                                <?php endif; ?>
                                <textarea name="contenu_message" class="message-input" rows="1" placeholder="Écrivez votre message..." required></textarea>
                                <button type="submit" class="btn btn-primary" style="padding: 0.75rem; border-radius: 50%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center;">
                                    <i data-lucide="send" style="width: 1.25rem; height: 1.25rem;"></i>
                                </button>
                            <?php else: ?>
                                <textarea class="message-input" rows="1" placeholder="Sélectionnez une conversation..." disabled></textarea>
                                <button type="button" class="btn btn-outline" disabled style="padding: 0.75rem; border-radius: 50%; aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; opacity: 0.5;">
                                    <i data-lucide="send" style="width: 1.25rem; height: 1.25rem;"></i>
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="/assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto scroll to bottom
            const messagesContainer = document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
            
            // Auto resize textarea
            const messageInput = document.querySelector('.message-input');
            if (messageInput) {
                messageInput.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });

                messageInput.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        const val = messageInput.value.trim();
                        if (val) {
                            document.getElementById('message-form').submit();
                        }
                    }
                });
            }
            
            // Mobile sidebar toggle
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const conversationsSidebar = document.querySelector('.conversations-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            
            if (mobileMenuToggle && conversationsSidebar) {
                mobileMenuToggle.addEventListener('click', function() {
                    conversationsSidebar.classList.add('active');
                });
            }
            
            if (closeSidebar && conversationsSidebar) {
                closeSidebar.addEventListener('click', function() {
                    conversationsSidebar.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>
