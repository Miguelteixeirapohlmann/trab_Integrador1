<?php
/**
 * Template Head Completo
 * Include este arquivo em todas as páginas
 */

// Parâmetros configuráveis
$page_title = $page_title ?? 'Sistema Imobiliário';
$page_description = $page_description ?? 'Sistema completo para gerenciamento de imóveis';
$page_author = $page_author ?? 'Sistema Imobiliário';
$include_admin_styles = $include_admin_styles ?? false;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="author" content="<?php echo htmlspecialchars($page_author); ?>">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#ff7b00">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <link rel="apple-touch-icon" href="assets/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" integrity="sha384-iw3OoTErCYJB9mCa8LNS2hbsQ7M3C8n8YtV4hKjbS8ggkIgQ5lV7hMl4Gp/rX" crossorigin="anonymous">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    
    <!-- AOS (Animate On Scroll) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
    
    <!-- Base Styles -->
    <style>
        :root {
            --primary-color: #ff7b00;
            --primary-dark: #e56900;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gradient-primary: linear-gradient(45deg, #ff7b00, #ff9500);
            --gradient-secondary: linear-gradient(45deg, #6c757d, #5a6268);
            --border-radius: 12px;
            --box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            --box-shadow-hover: 0 8px 25px rgba(0, 0, 0, 0.15);
            --transition: all 0.3s ease;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--light-color);
            line-height: 1.6;
            color: var(--dark-color);
        }
        
        /* Loading States */
        .loading {
            opacity: 0.7;
            pointer-events: none;
            position: relative;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            transform: translate(-50%, -50%);
        }
        
        @keyframes spin {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--box-shadow-hover);
            transform: translateY(-2px);
        }
        
        /* Buttons */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 0.5rem 1.5rem;
            transition: var(--transition);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--gradient-primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(255, 123, 0, 0.3);
            color: white;
        }
        
        .btn-details {
            background: var(--gradient-primary);
            border: none;
            color: white;
            font-weight: 500;
        }
        
        .btn-details:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(255, 123, 0, 0.3);
            color: white;
        }
        
        /* Forms */
        .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 123, 0, 0.25);
        }
        
        /* Navigation */
        .navbar-brand {
            font-weight: 600;
            color: white !important;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Tables */
        .table th {
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            background-color: var(--dark-color);
            color: white;
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) > td {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        /* Badges */
        .badge {
            font-weight: 500;
            padding: 0.5em 0.75em;
        }
        
        /* Carousel */
        .carousel-img-fixed {
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .product-img-preview {
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .carousel-control-prev,
        .carousel-control-next {
            width: 40px;
            height: 40px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
            transition: var(--transition);
        }
        
        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
        }
        
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 20px;
            height: 20px;
        }
        
        .carousel-indicators button {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin: 0 3px;
            opacity: 0.5;
            transition: var(--transition);
        }
        
        .carousel-indicators button.active {
            opacity: 1;
        }
        
        /* Modals */
        .modal-content {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }
        
        .modal-footer {
            border-top: 1px solid #dee2e6;
        }
        
        /* Property Cards */
        .property-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .property-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-hover);
        }
        
        .property-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .property-price {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .price-sale {
            color: var(--success-color);
        }
        
        .price-rent {
            color: var(--primary-color);
        }
        
        /* Utilities */
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .carousel-img-fixed {
                height: 150px;
            }
            
            .property-card {
                margin-bottom: 1rem;
            }
            
            .btn {
                padding: 0.4rem 1rem;
                font-size: 0.9rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
        
        @media (max-width: 576px) {
            .carousel-img-fixed {
                height: 120px;
            }
            
            .btn-sm {
                padding: 0.3rem 0.8rem;
                font-size: 0.8rem;
            }
        }
        
        /* Accessibility */
        .btn:focus,
        .form-control:focus,
        .form-select:focus {
            outline: none;
        }
        
        .visually-hidden {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }
    </style>
    
    <?php if ($include_admin_styles): ?>
    <!-- Admin-specific styles -->
    <style>
        .admin-sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--dark-color) 0%, #343a40 100%);
        }
        
        .admin-content {
            padding: 2rem;
        }
        
        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            text-align: center;
        }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: white;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            color: var(--secondary-color);
            font-weight: 500;
        }
    </style>
    <?php endif; ?>
    
    <!-- Preload critical resources -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" href="css/styles.css" as="style">
    
    <!-- Schema.org markup for better SEO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "RealEstateAgent",
        "name": "Sistema Imobiliário",
        "description": "<?php echo htmlspecialchars($page_description); ?>",
        "url": "<?php echo (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST']; ?>"
    }
    </script>
</head>