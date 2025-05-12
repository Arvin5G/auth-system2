<?php
// components/styles.php
?>
<style>
    :root {
        --primary-color: #800000;
        --secondary-color:rgb(44, 3, 3);
        --accent-color: #4895ef;
        --dark-color: #1b263b;
        --light-color: #f8f9fa;
        --danger-color: #800000;
        --sidebar-width: 250px;
        --header-height: 70px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #f5f7fa;
        color: #333;
        overflow-x: hidden;
    }

    .container {
        display: flex;
        min-height: 100vh;
    }

    /* Sidebar Styles with Animation */
    .sidebar {
        width: var(--sidebar-width);
        background: linear-gradient(135deg, var(--primary-color));
        color: white;
        position: fixed;
        height: 100vh;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 100;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        transform: translateX(0);
    }

    .sidebar.collapsed {
        transform: translateX(calc(-1 * var(--sidebar-width)));
        box-shadow: none;
    }

    .sidebar-header {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-menu {
        padding: 20px 0;
    }

    .sidebar-menu ul {
        list-style: none;
    }

    .sidebar-menu li a {
        display: block;
        padding: 15px 20px;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
        font-size: 15px;
    }

    .sidebar-menu li a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        padding-left: 25px;
    }

    .sidebar-menu li a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .sidebar-menu li.active a {
        background-color: rgba(255, 255, 255, 0.2);
        border-left: 3px solid white;
    }

    /* Main Content Styles */
    .main-content {
        flex: 1;
        margin-left: var(--sidebar-width);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .main-content.expanded {
        margin-left: 0;
    }

    /* Header Styles */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 30px;
        height: var(--header-height);
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 90;
    }

    .header-left {
        display: flex;
        align-items: center;
    }

    .header-left h2 {
        color: var(--dark-color);
        font-size: 22px;
    }

    .header-right {
        display: flex;
        align-items: center;
    }

    .profile {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid var(--primary-color);
    }

    .profile-name {
        font-weight: 500;
        margin-right: 10px;
    }

    /* Content Area Styles */
    .content-wrapper {
        padding: 30px;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .page-title h1 {
        color: var(--dark-color);
        font-size: 28px;
        font-weight: 600;
    }

    .page-title p {
        color: #6c757d;
        font-size: 14px;
    }

    /* Card Styles */
    .card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 25px;
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .card-header h3 {
        font-size: 18px;
        color: var(--dark-color);
    }

    .user-info p {
        margin-bottom: 15px;
        font-size: 15px;
    }

    .user-info strong {
        color: var(--dark-color);
        font-weight: 600;
        display: inline-block;
        width: 120px;
    }

    /* Button Styles */
    .btn {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        transform: translateY(-2px);
    }

    .btn-danger {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-danger:hover {
        background-color: #5c0000;
        transform: translateY(-2px);
    }

    /* Alert Styles */
    .alert {
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert.success {
        background-color: #d1fae5;
        color: #065f46;
        border-left: 4px solid #10b981;
    }

    /* Menu Toggle Styles */
    .menu-toggle {
        background: none;
        border: none;
        font-size: 20px;
        color: var(--dark-color);
        cursor: pointer;
        margin-right: 20px;
        transition: transform 0.3s;
    }

    .menu-toggle.rotated {
        transform: rotate(90deg);
    }

    /* Profile Dropdown */
    .profile-dropdown {
        position: relative;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 50px;
        background-color: white;
        border-radius: 5px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        width: 200px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
        z-index: 100;
    }

    .profile-dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        top: 60px;
    }

    .dropdown-menu a {
        display: block;
        padding: 10px 15px;
        color: #555;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.3s;
    }

    .dropdown-menu a:hover {
        background-color: #f8f9fa;
        color: var(--primary-color);
        padding-left: 20px;
    }

    .dropdown-menu a i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .dropdown-divider {
        height: 1px;
        background-color: #eee;
        margin: 5px 0;
    }

    /* Overlay for mobile */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 99;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s;
    }

    .sidebar-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    /* Responsive Styles */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(calc(-1 * var(--sidebar-width)));
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
        }
    }
</style>