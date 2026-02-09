<?php
/**
 * HOMEPAGE - Sistema Gestione Biblioteca
 * File: index.php
 * 
 * Pagina principale del sito, accessibile a tutti (loggati e non)
 */

// Avvia la sessione per controllare se l'utente è già loggato
session_start();

// Verifica se l'utente è loggato
$is_logged_in = isset($_SESSION['user_id']);
$user_name = $is_logged_in ? $_SESSION['full_name'] : '';
$is_admin = $is_logged_in && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioTech - Sistema Gestione Biblioteca</title>
    <link rel="stylesheet" href="css/style.css">
    
    <!-- Font Google: Playfair Display per titoli + Open Sans per testo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    
    <!-- HEADER / NAVIGAZIONE -->
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <h1>📚 BiblioTech</h1>
                </div>
                
                <!-- Navigazione -->
                <nav class="main-nav">
                    <?php if ($is_logged_in): ?>
                        <!-- Menu per utenti loggati -->
                        <a href="pages/catalog.php" class="nav-link">Catalogo</a>
                        <a href="pages/my_loans.php" class="nav-link">I Miei Prestiti</a>
                        
                        <?php if ($is_admin): ?>
                            <!-- Link extra per admin -->
                            <a href="pages/admin_books.php" class="nav-link admin">Gestione Libri</a>
                            <a href="pages/admin_loans.php" class="nav-link admin">Gestione Prestiti</a>
                        <?php endif; ?>
                        
                        <!-- Info utente e logout -->
                        <div class="user-menu">
                            <span class="user-greeting">Ciao, <?php echo htmlspecialchars($user_name); ?>!</span>
                            <a href="logout.php" class="btn-logout">Esci</a>
                        </div>
                    <?php else: ?>
                        <!-- Menu per visitatori non loggati -->
                        <a href="pages/login.php" class="btn-primary">Accedi</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <!-- SEZIONE HERO (PRINCIPALE) -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h2 class="hero-title">
                        La tua biblioteca<br>
                        <span class="highlight">universitaria digitale</span>
                    </h2>
                    <p class="hero-description">
                        Esplora migliaia di libri, richiedi prestiti online e gestisci 
                        le tue letture in modo semplice e veloce. La conoscenza a portata di click.
                    </p>
                    
                    <div class="hero-buttons">
                        <?php if (!$is_logged_in): ?>
                            <a href="pages/login.php" class="btn-large btn-primary">
                                Inizia ora
                            </a>
                            <a href="#features" class="btn-large btn-secondary">
                                Scopri di più
                            </a>
                        <?php else: ?>
                            <a href="pages/catalog.php" class="btn-large btn-primary">
                                Esplora il catalogo
                            </a>
                            <a href="pages/my_loans.php" class="btn-large btn-secondary">
                                I tuoi prestiti
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="hero-visual">
                    <!-- Decorazione visiva: stack di libri stilizzato -->
                    <div class="books-stack">
                        <div class="book book-1"></div>
                        <div class="book book-2"></div>
                        <div class="book book-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEZIONE STATISTICHE -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">500+</div>
                    <div class="stat-label">Libri disponibili</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">Studenti attivi</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Accesso online</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">30gg</div>
                    <div class="stat-label">Durata prestito</div>
                </div>
            </div>
        </div>
    </section>

    <!-- SEZIONE FUNZIONALITÀ -->
    <section id="features" class="features">
        <div class="container">
            <h2 class="section-title">Come funziona</h2>
            <p class="section-subtitle">
                Gestire i tuoi prestiti non è mai stato così semplice
            </p>
            
            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3 class="feature-title">Cerca e scopri</h3>
                    <p class="feature-description">
                        Naviga il catalogo completo, filtra per categoria e trova 
                        il libro perfetto per te in pochi secondi.
                    </p>
                </div>
                
                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3 class="feature-title">Richiedi online</h3>
                    <p class="feature-description">
                        Invia la richiesta di prestito con un click. Riceverai 
                        una notifica quando il libro sarà pronto per il ritiro.
                    </p>
                </div>
                
                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3 class="feature-title">Gestisci tutto</h3>
                    <p class="feature-description">
                        Tieni traccia dei tuoi prestiti attivi, controlla le 
                        scadenze e visualizza lo storico delle tue letture.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- SEZIONE CATEGORIE POPOLARI -->
    <section class="categories">
        <div class="container">
            <h2 class="section-title">Categorie popolari</h2>
            
            <div class="categories-grid">
                <div class="category-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3>📖 Romanzi</h3>
                    <p>120+ titoli</p>
                </div>
                
                <div class="category-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h3>🎭 Classici</h3>
                    <p>85+ titoli</p>
                </div>
                
                <div class="category-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h3>🧪 Saggistica</h3>
                    <p>95+ titoli</p>
                </div>
                
                <div class="category-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <h3>🐉 Fantasy</h3>
                    <p>110+ titoli</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="main-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>BiblioTech</h4>
                    <p>Sistema di gestione biblioteca universitaria</p>
                    <p class="footer-note">Progetto d'esame - Università 2026</p>
                </div>
                
                <div class="footer-section">
                    <h4>Link Utili</h4>
                    <ul class="footer-links">
                        <?php if (!$is_logged_in): ?>
                            <li><a href="pages/login.php">Accedi</a></li>
                        <?php else: ?>
                            <li><a href="pages/catalog.php">Catalogo</a></li>
                            <li><a href="pages/my_loans.php">I Miei Prestiti</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Contatti</h4>
                    <p>📧 biblioteca@università.it</p>
                    <p>📞 +39 123 456 7890</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2026 BiblioTech. Tutti i diritti riservati.</p>
            </div>
        </div>
    </footer>

    <!-- Script per animazioni leggere -->
    <script>
        // Animazione fade-in per le card quando entrano in viewport
        // (Semplice, comprensibile, niente librerie esterne)
        
        document.addEventListener('DOMContentLoaded', function() {
            // Osserva quando gli elementi entrano nel viewport
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            // Applica l'osservatore a tutte le card
            const cards = document.querySelectorAll('.feature-card, .category-card, .stat-card');
            cards.forEach(function(card) {
                observer.observe(card);
            });
        });
    </script>
</body>
</html>