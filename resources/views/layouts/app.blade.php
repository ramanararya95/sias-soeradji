<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'SIAS Soeradji')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    /* Reset CSS */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    html, body {
      height: 100%;
      overflow-x: hidden;
    }
    
    :root {
      --primary-color: #1e40af;
      --secondary-color: #3b82f6;
      --accent-color: #60a5fa;
      --sidebar-width: 280px;
      --header-height: 64px;
      --transition-speed: 0.3s;
      --sidebar-bg: #f8fafc;
      --sidebar-text: #334155;
      --sidebar-hover: #e2e8f0;
      --sidebar-active: #cbd5e1;
      --zoom-level: 1;
      --vh: 1vh; /* DIUBAH: Tambahkan variabel vh untuk mengontrol tinggi viewport */
    }
    
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f1f5f9;
      transform: scale(var(--zoom-level));
      transform-origin: top left;
      width: calc(100% / var(--zoom-level));
      transition: transform 0.3s ease;
      min-height: 100vh;
    }
    
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh; /* Kita akan mengatur ini dengan JavaScript */
      width: var(--sidebar-width);
      background-color: var(--sidebar-bg);
      box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
      z-index: 1000; /* Pastikan ini cukup tinggi */
      transition: transform var(--transition-speed) cubic-bezier(0.4, 0, 0.2, 1);
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    
    .sidebar-header {
      padding: 20px;
      border-bottom: 1px solid #e2e8f0;
      background-color: var(--sidebar-bg);
      position: relative;
      z-index: 10;
      flex-shrink: 0;
    }
    
    .sidebar-menu {
      padding: 10px 0;
      flex: 1;
      overflow-y: auto;
      overflow-x: hidden;
    }
    
    .menu-item {
      display: flex;
      align-items: center;
      padding: 14px 20px;
      color: var(--sidebar-text);
      text-decoration: none;
      transition: all var(--transition-speed);
      position: relative;
      margin: 5px 15px;
      border-radius: 8px;
    }
    
    .menu-item:hover {
      background-color: var(--sidebar-hover);
      color: var(--sidebar-text);
    }
    
    .menu-item.active {
      background-color: var(--sidebar-active);
      color: var(--sidebar-only); /* Sepertinya ada typo, seharusnya --sidebar-text */
      font-weight: 500;
    }
    
    .menu-item.active::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      height: 100%;
      width: 4px;
      background-color: var(--primary-color);
      border-radius: 0 4px 4px 0;
    }
    
    .menu-item i {
      margin-right: 18px;
      font-size: 22px;
      color: var(--sidebar-text);
    }
    
    .submenu {
      max-height: 0;
      overflow: hidden;
      transition: max-height var(--transition-speed) ease-in-out;
    }
    
    .submenu.open {
      max-height: 500px;
    }
    
    .submenu-item {
      display: flex;
      align-items: center;
      padding: 12px 20px 12px 58px;
      color: var(--sidebar-text);
      text-decoration: none;
      transition: all var(--transition-speed);
      font-size: 15px;
    }
    
    .submenu-item:hover {
      background-color: var(--sidebar-hover);
      color: var(--sidebar-text);
    }
    
    .submenu-item.active {
      background-color: var(--sidebar-active);
      color: var(--sidebar-text);
    }
    
    /* Header - Fixed Position at Top */
    .header {
      position: fixed;
      top: 0;
      left: var(--sidebar-width);
      right: 0;
      height: var(--header-height);
      background-color: white;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
      z-index: 999;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
      transition: left var(--transition-speed);
    }
    
    .header-left {
      display: flex;
      align-items: center;
    }
    
    .header-right {
      display: flex;
      align-items: center;
      gap: 16px;
    }
    
      /* Pastikan main content berada di bawah sidebar */
    .main-content {
      position: relative;
      z-index: 1; /* Pastikan ini di bawah sidebar */
      margin-left: var(--sidebar-width);
      margin-top: var(--header-height);
      padding: 24px;
      min-height: calc(100vh - var(--header-height));
      transition: margin-left var(--transition-speed);
      box-sizing: border-box;
      overflow-y: auto;
    }
    
    /* User Profile Card in Sidebar */
    .user-profile-card {
      background: white;
      border-radius: 12px;
      padding: 18px;
      margin: 18px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
      flex-shrink: 0;
    }
    
    .user-avatar {
      width: 52px;
      height: 52px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #e2e8f0;
    }
    
    /* Pastikan welcome card tidak menutupi sidebar */
      .welcome-card {
        position: relative;
        z-index: 1; /* Kurangi z-index agar tidak menutupi sidebar */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        color: white;
        overflow: hidden;
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
      }
    
    .welcome-card::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      transform: translate(50%, -50%);
    }
    
    .welcome-card h2 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 8px;
    }
    
    .welcome-card p {
      font-size: 16px;
      opacity: 0.9;
      margin-bottom: 16px;
    }
    
    .welcome-card .stats {
      display: flex;
      gap: 16px;
    }
    
    .welcome-card .stat-item {
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(10px);
      border-radius: 8px;
      padding: 12px 16px;
    }
    
    .welcome-card .stat-label {
      font-size: 12px;
      opacity: 0.8;
      margin-bottom: 4px;
    }
    
    .welcome-card .stat-value {
      font-size: 18px;
      font-weight: 600;
    }
    
    /* Chat Widget in Header */
    .chat-control {
      position: relative;
    }
    
    .chat-button {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: var(--primary-color);
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .chat-button:hover {
      background-color: var(--secondary-color);
      transform: scale(1.05);
    }
    
    .chat-button i {
      font-size: 18px;
    }
    
    .chat-badge {
      position: absolute;
      top: 0;
      right: 0;
      width: 18px;
      height: 18px;
      background-color: #ef4444;
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      font-weight: bold;
      transform: translate(25%, -25%);
    }
    
    /* Chat User Selection Dropdown */
    .chat-user-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      margin-top: 8px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      z-index: 1000;
      min-width: 300px;
      max-width: 350px;
      max-height: 400px;
      display: flex;
      flex-direction: column;
    }
    
    .chat-dropdown-header {
      padding: 16px;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .chat-dropdown-header h3 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
      color: #1f2937;
    }
    
    .chat-dropdown-header .close-button {
      background: none;
      border: none;
      color: #6b7280;
      cursor: pointer;
      font-size: 18px;
      padding: 4px;
      border-radius: 4px;
      transition: background-color 0.2s;
    }
    
    .chat-dropdown-header .close-button:hover {
      background-color: #f3f4f6;
    }
    
    .chat-user-list {
      flex: 1;
      overflow-y: auto;
      padding: 8px;
    }
    
    .chat-user-item {
      display: flex;
      align-items: center;
      padding: 12px;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .chat-user-item:hover {
      background-color: #f3f4f6;
    }
    
    .chat-user-item.active {
      background-color: #eff6ff;
    }
    
    .chat-user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 12px;
      position: relative;
    }
    
    .chat-user-info {
      flex: 1;
    }
    
    .chat-user-name {
      font-size: 14px;
      font-weight: 500;
      color: #1f2937;
      margin-bottom: 2px;
    }
    
    .chat-user-status {
      font-size: 12px;
      color: #6b7280;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    
    .online-indicator {
      width: 8px;
      height: 8px;
      background-color: #10b981;
      border-radius: 50%;
    }
    
    .offline-indicator {
      width: 8px;
      height: 8px;
      background-color: #6b7280;
      border-radius: 50%;
    }
    
    .chat-user-actions {
      display: flex;
      gap: 8px;
    }
    
    .chat-user-action {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background-color: #f3f4f6;
      color: #6b7280;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.2s;
    }
    
    .chat-user-action:hover {
      background-color: #e5e7eb;
      color: #374151;
    }
    
    .chat-user-action.primary {
      background-color: var(--primary-color);
      color: white;
    }
    
    .chat-user-action.primary:hover {
      background-color: var(--secondary-color);
    }
    
    .no-users-message {
      padding: 24px;
      text-align: center;
      color: #6b7280;
    }
    
    .no-users-message i {
      font-size: 24px;
      margin-bottom: 8px;
      display: block;
    }
    
    /* Chat Container */
    .chat-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 350px;
      height: 450px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
      display: flex;
      flex-direction: column;
      overflow: hidden;
      transform: scale(0.8) translateY(20px);
      opacity: 0;
      pointer-events: none;
      transition: all 0.3s ease;
      z-index: 1001;
    }
    
    .chat-container.show {
      transform: scale(1) translateY(0);
      opacity: 1;
      pointer-events: auto;
    }
    
    .chat-header {
      background-color: var(--primary-color);
      color: white;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .chat-header .user-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    
    .chat-header .user-info img {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
    }
    
    .chat-header .user-info .user-details h4 {
      margin: 0;
      font-size: 16px;
      font-weight: 600;
    }
    
    .chat-header .user-info .user-details p {
      margin: 0;
      font-size: 12px;
      opacity: 0.8;
    }
    
    .chat-header .close-button {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 18px;
    }
    
    .chat-messages {
      flex: 1;
      padding: 16px;
      overflow-y: auto;
      background-color: #f9fafb;
    }
    
    .chat-message {
      margin-bottom: 16px;
      display: flex;
      gap: 8px;
    }
    
    .chat-message.sent {
      flex-direction: row-reverse;
    }
    
    .chat-message .avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
    }
    
    .chat-message .message-content {
      max-width: 70%;
    }
    
    .chat-message .message-bubble {
      padding: 10px 14px;
      border-radius: 18px;
      font-size: 14px;
      line-height: 1.4;
    }
    
    .chat-message.received .message-bubble {
      background-color: white;
      color: #1f2937;
      border-top-left-radius: 4px;
    }
    
    .chat-message.sent .message-bubble {
      background-color: var(--primary-color);
      color: white;
      border-top-right-radius: 4px;
    }
    
    .chat-message .message-time {
      font-size: 11px;
      color: #6b7280;
      margin-top: 4px;
      text-align: right;
    }
    
    .chat-message.sent .message-time {
      text-align: left;
    }
    
    .chat-input {
      padding: 16px;
      border-top: 1px solid #e5e7eb;
      display: flex;
      gap: 8px;
    }
    
    .chat-input input {
      flex: 1;
      border: 1px solid #d1d5db;
      border-radius: 24px;
      padding: 8px 16px;
      font-size: 14px;
      outline: none;
    }
    
    .chat-input input:focus {
      border-color: var(--primary-color);
    }
    
    .chat-input button {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background-color: var(--primary-color);
      color: white;
      border: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }
    
    /* Zoom Control */
    .zoom-control {
      position: relative;
    }
    
    .zoom-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      margin-top: 8px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      overflow: hidden;
      z-index: 50;
      min-width: 150px;
    }
    
    .zoom-option {
      display: flex;
      align-items: center;
      padding: 10px 16px;
      cursor: pointer;
      transition: background-color 0.2s;
    }
    
    .zoom-option:hover {
      background-color: #f3f4f6;
    }
    
    .zoom-option.active {
      background-color: #eff6ff;
      color: var(--primary-color);
    }
    
    .zoom-option i {
      margin-right: 12px;
      font-size: 16px;
    }
    
    .zoom-option span {
      font-size: 14px;
      font-weight: 500;
    }
    
    /* Mobile Responsiveness */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.open {
        transform: translateX(0);
      }
      
      .header {
        left: 0;
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .chat-container {
        width: calc(100vw - 40px);
        right: 20px;
        left: 20px;
      }
      
      .chat-user-dropdown {
        min-width: 280px;
        max-width: 300px;
      }
    }
    
    /* Dark Mode */
    .dark {
      background-color: #111827;
      color: #f9fafb;
    }
    
    .dark .sidebar {
      background-color: #1f2937;
    }
    
    .dark .sidebar-header {
      background-color: #1f2937;
      border-bottom-color: #374151;
    }
    
    .dark .header {
      background-color: #1f2937;
      color: #f9fafb;
    }
    
    .dark .main-content {
      background-color: #111827;
    }
    
    .dark .user-profile-card {
      background-color: #1f2937;
    }
    
    .dark .menu-item {
      color: #d1d5db;
    }
    
    .dark .menu-item:hover {
      background-color: #374151;
    }
    
    .dark .menu-item.active {
      background-color: #4b5563;
    }
    
    .dark .submenu-item {
      color: #d1d5db;
    }
    
    .dark .submenu-item:hover {
      background-color: #374151;
    }
    
    .dark .submenu-item.active {
      background-color: #4b5563;
    }
    
    .dark .chat-container {
      background-color: #1f2937;
    }
    
    .dark .chat-messages {
      background-color: #111827;
    }
    
    .dark .chat-message.received .message-bubble {
      background-color: #374151;
      color: #f9fafb;
    }
    
    .dark .chat-input {
      border-top-color: #374151;
    }
    
    .dark .chat-input input {
      background-color: #374151;
      border-color: #4b5563;
      color: #f9fafb;
    }
    
    .dark .chat-user-dropdown {
      background-color: #1f2937;
    }
    
    .dark .chat-dropdown-header {
      border-bottom-color: #374151;
    }
    
    .dark .chat-dropdown-header h3 {
      color: #f9fafb;
    }
    
    .dark .chat-dropdown-header .close-button {
      color: #9ca3af;
    }
    
    .dark .chat-dropdown-header .close-button:hover {
      background-color: #374151;
    }
    
    .dark .chat-user-item:hover {
      background-color: #374151;
    }
    
    .dark .chat-user-item.active {
      background-color: #1e3a8a;
    }
    
    .dark .chat-user-action {
      background-color: #374151;
      color: #9ca3af;
    }
    
    .dark .chat-user-action:hover {
      background-color: #4b5563;
      color: #d1d5db;
    }
    
    .dark .zoom-dropdown {
      background-color: #1f2937;
    }
    
    .dark .zoom-option:hover {
      background-color: #374151;
    }
    
    .dark .zoom-option.active {
      background-color: #1e3a8a;
      color: #60a5fa;
    }
    
    /* Loading Animation */
    .loading-spinner {
      border: 3px solid rgba(0, 0, 0, 0.1);
      border-radius: 50%;
      border-top: 3px solid var(--primary-color);
      width: 20px;
      height: 20px;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); } /* DIUBAH: dari customRotate ke rotate */
    }
    
    /* Card Styles */
    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      padding: 20px;
      margin-bottom: 20px;
      transition: all var(--transition-speed);
    }
    
    .card:hover {
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
    }
    
    .dark .card {
      background-color: #1f2937;
      color: #f9fafb;
    }
    
    /* Notification Badge */
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: #ef4444;
      color: white;
      border-radius: 50%;
      width: 18px;
      height: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 10px;
      font-weight: bold;
    }
    
    /* Transition for page content */
    .page-content {
      opacity: 1;
      transition: opacity var(--transition-speed);
    }
    
    .page-content.loading {
      opacity: 0.5;
      pointer-events: none;
    }
    
    /* Custom scrollbar for sidebar menu */
    .sidebar-menu::-webkit-scrollbar {
      width: 6px;
    }
    
    .sidebar-menu::-webkit-scrollbar-track {
      background: rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }
    
    .sidebar-menu::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.2);
      border-radius: 10px;
    }
    
    .sidebar-menu::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 0, 0, 0.3);
    }
    
    .dark .sidebar-menu::-webkit-scrollbar-track {
      background: rgba(255, 255, 255, 0.1);
    }
    
    .dark .sidebar-menu::-webkit-scrollbar-thumb {
      background: rgba(255, 255, 255, 0.2);
    }
    
    .dark .sidebar-menu::-webkit-scrollbar-thumb:hover {
      background: rgba(255, 255, 255, 0.3);
    }
    
    /* Footer styling */
    .app-footer {
      background-color: #f8fafc;
      border-top: 1px solid #e2e8f0;
      padding: 16px 24px;
      color: #64748b;
      font-size: 14px;
      text-align: center;
      margin-top: auto;
    }
    
    .dark .app-footer {
      background-color: #1f2937;
      border-top-color: #374151;
      color: #9ca3af;
    }
  </style>
  
  @stack('styles')
</head>

<body class="bg-gray-50" x-data="layoutApp()" :class="{ 'dark': darkMode }">
  <!-- Sidebar -->
  <aside class="sidebar" :class="{ 'open': sidebarOpen }">
    <!-- Logo Section -->
    <div class="sidebar-header">
      <div class="flex items-center justify-center">
        <a href="{{ route('dashboard') }}" class="flex items-center">
          <img src="{{ asset('img/logo-soeradji.png') }}" alt="SIAS Soeradji" class="h-10">
        </a>
      </div>
    </div>
    
    <!-- User Profile Card -->
    <div class="user-profile-card">
      <div class="flex items-center">
        @if(auth()->user()->profile && auth()->user()->profile->foto)
          <img src="{{ asset('storage/profiles/' . auth()->user()->profile->foto) }}" alt="Profile" class="user-avatar">
        @else
          <div class="user-avatar bg-blue-600 flex items-center justify-center">
            <span class="text-white font-bold text-lg">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
          </div>
        @endif
        <div class="ml-3">
          <p class="font-medium text-gray-800">{{ auth()->user()->nama_lengkap }}</p>
          <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
        </div>
      </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="sidebar-menu">
      <!-- Beranda -->
      <a href="{{ route('dashboard') }}" class="menu-item @if(request()->routeIs('dashboard')) active @endif" @click="navigateToPage(event, '{{ route('dashboard') }}')">
        <i class="fas fa-home"></i>
        <span>Beranda</span>
      </a>
      
      <!-- Registrasi Arsip -->
      @if(in_array(auth()->user()->role, ['admin', 'arsiparis', 'pejabat_struktural']))
      <div class="menu-section">
        <div class="menu-item" @click="toggleSubmenu('registrasi-arsip')">
          <i class="fas fa-folder-plus"></i>
          <span>Registrasi Arsip</span>
          <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-180': submenus['registrasi-arsip'] }"></i>
        </div>
        <div class="submenu" :class="{ 'open': submenus['registrasi-arsip'] }">
          <a href="{{ route('arsip.aktif.create') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('arsip.aktif.create') }}')">
            <i class="fas fa-folder-open"></i>
            <span>Arsip Aktif</span>
          </a>
          <a href="{{ route('arsip.inaktif.create') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('arsip.inaktif.create') }}')">
            <i class="fas fa-archive"></i>
            <span>Arsip Inaktif</span>
          </a>
          <a href="{{ route('arsip.vital.create') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('arsip.vital.create') }}')">
            <i class="fas fa-shield-alt"></i>
            <span>Arsip Vital</span>
          </a>
          <a href="{{ route('arsip.alihmedia.create') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('arsip.alihmedia.create') }}')">
            <i class="fas fa-exchange-alt"></i>
            <span>Arsip Alih Media</span>
          </a>
        </div>
      </div>
      @endif
      
      <!-- Surat Tugas -->
      @if(in_array(auth()->user()->role, ['admin', 'arsiparis', 'pejabat_struktural']))
      <div class="menu-section">
        <div class="menu-item" @click="toggleSubmenu('surat-tugas')">
          <i class="fas fa-envelope-open-text"></i>
          <span>Surat Tugas</span>
          <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-180': submenus['surat-tugas'] }"></i>
        </div>
        <div class="submenu" :class="{ 'open': submenus['surat-tugas'] }">
          <a href="{{ route('surat_tugas.form') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('surat_tugas.form') }}')">
            <i class="fas fa-file-alt"></i>
            <span>Buat Surat Tugas</span>
          </a>
          <a href="{{ route('log_surat_tugas.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('log_surat_tugas.index') }}')">
            <i class="fas fa-history"></i>
            <span>Log Surat Tugas</span>
          </a>
        </div>
      </div>
      @endif
      
      <!-- Watermark -->
      <div class="menu-section">
        <div class="menu-item" @click="toggleSubmenu('watermark')">
          <i class="fas fa-stamp"></i>
          <span>Watermark</span>
          <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-180': submenus['watermark'] }"></i>
        </div>
        <div class="submenu" :class="{ 'open': submenus['watermark'] }">
          <a href="{{ route('watermark.image.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('watermark.image.index') }}')">
            <i class="fas fa-image"></i>
            <span>Watermark Gambar</span>
          </a>
          <a href="{{ route('watermark.text.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('watermark.text.index') }}')">
            <i class="fas fa-file-alt"></i>
            <span>Watermark Dokumen</span>
          </a>
          <a href="{{ route('watermark.logs.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('watermark.logs.index') }}')">
            <i class="fas fa-history"></i>
            <span>Log Watermark</span>
          </a>
        </div>
      </div>
      
      <!-- Berita Acara -->
      <div class="menu-section">
        <div class="menu-item" @click="toggleSubmenu('berita-acara')">
          <i class="fas fa-file-contract"></i>
          <span>Berita Acara</span>
          <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-0': submenus['berita-acara'] }"></i>
        </div>
        <div class="submenu" :class="{ 'open': submenus['berita-acara'] }">
          <a href="{{ route('berita_acara.pemindahan.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('berita_acara.pemindahan.index') }}')">
            <i class="fas fa-truck"></i>
            <span>Pemindahan</span>
          </a>
          <a href="{{ route('berita_acara.pemusnahan.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('berita_acara.pemusnahan.index') }}')">
            <i class="fas fa-trash"></i>
            <span>Pemusnahan</span>
          </a>
          <a href="{{ route('berita_acara.alihmedia.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('berita_acara.alihmedia.index') }}')">
            <i class="fas fa-exchange-alt"></i>
            <span>Alih Media</span>
          </a>
        </div>
      </div>
      
      <!-- Laporan Kearsipan -->
      <div class="menu-section">
        <div class="menu-item" @click="toggleSubmenu('laporan')">
          <i class="fas fa-chart-bar"></i>
          <span>Laporan</span>
          <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-0': submenus['laporan'] }"></i>
        </div>
        <div class="submenu" :class="{ 'open': submenus['laporan'] }">
          <a href="{{ route('laporan.arsip.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('laporan.arsip.index') }}')">
            <i class="fas fa-file-archive"></i>
            <span>Laporan Arsip</span>
          </a>
          <a href="{{ route('laporan.aktivitas.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('laporan.aktivitas.index') }}')">
            <i class="fas fa-history"></i>
            <span>Laporan Aktivitas</span>
          </a>
        </div>
      </div>
      
      <!-- Pengaturan -->
      <div class="menu-section">
        <div class="menu-item" @click="toggleSubmenu('pengaturan')">
          <i class="fas fa-cog"></i>
          <span>Pengaturan</span>
          <i class="fas fa-chevron-down ml-auto" :class="{ 'rotate-0': submenus['pengaturan'] }"></i>
        </div>
        <div class="submenu" :class="{ 'open': submenus['pengaturan'] }">
          @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.users.index') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('admin.users.index') }}')">
            <i class="fas fa-users-cog"></i>
            <span>Role & Jabatan</span>
          </a>
          @endif
          <a href="{{ route('admin.pengaturan.background') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('admin.pengaturan.background') }}')">
            <i class="fas fa-image"></i>
            <span>Pengaturan Background</span>
          </a>
          <a href="{{ route('admin.pengaturan.nomor') }}" class="submenu-item" @click="navigateToPage(event, '{{ route('admin.pengaturan.nomor') }}')">
            <i class="fas fa-hashtag"></i>
            <span>Pengaturan Nomor Arsip</span>
          </a>
        </div>
      </div>
    </nav>
  </aside>
  
  <!-- Header -->
  <header class="header">
    <div class="header-left">
      <button @click="toggleSidebar()" class="p-2 rounded-md hover:bg-gray-100 lg:hidden">
        <i class="fas fa-bars text-gray-600"></i>
      </button>
      <div class="ml-4">
        <h1 class="text-xl font-semibold text-gray-800" x-text="pageTitle"></h1>
        <p class="text-sm text-gray-500" x-text="pageSubtitle"></p>
      </div>
    </div>
    
    <div class="header-right">
      <!-- Chat Control -->
      <div class="chat-control" x-data="chatWidget()">
        <!-- Chat Button -->
        <div class="chat-button" @click="toggleChatUserSelection()">
          <i class="fas fa-comments"></i>
          <span class="chat-badge" x-show="unreadMessages > 0" x-text="unreadMessages"></span>
        </div>
        
        <!-- Chat User Selection Dropdown -->
        <div x-show="showUserSelection" 
             @click.away="showUserSelection = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="chat-user-dropdown">
          <div class="chat-dropdown-header">
            <h3>Pilih Pengguna</h3>
            <button class="close-button" @click="showUserSelection = false">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="chat-user-list">
            <template x-for="user in onlineUsers" :key="user.id">
              <div class="chat-user-item" @click="openChatWithUser(user)">
                <img :src="user.avatar || '/img/default-avatar.png'" :alt="user.name" class="chat-user-avatar">
                <div class="chat-user-info">
                  <div class="chat-user-name" x-text="user.name"></div>
                  <div class="chat-user-status">
                    <span class="online-indicator"></span>
                    <span>Online</span>
                  </div>
                </div>
                </div>
                <div class="chat-user-actions">
                  <div class="chat-user-action primary" title="Mulai Chat">
                    <i class="fas fa-comment"></i>
                  </div>
                </div>
              </div>
            </template>
            
            <div x-show="onlineUsers.length === 0" class="no-users-message">
              <i class="fas fa-user-slash"></i>
              <p>Tidak ada pengguna online</p>
            </div>
          </div>
        </div>
        
        <!-- Chat Container -->
        <div class="chat-container" :class="{ 'show': chatOpen }">
          <div class="chat-header">
            <div class="user-info" x-show="selectedUser">
              <img :src="selectedUser.avatar || '/img/default-avatar.png'" :alt="selectedUser.name">
              <div class="user-details">
                <h4 x-text="selectedUser.name"></h4>
                <p x-show="selectedUser.online">Online</p>
                <p x-show="!selectedUser.online">Offline</p>
              </div>
            </div>
            <div class="user-info" x-show="!selectedUser">
              <i class="fas fa-comments"></i>
              <div class="user-details">
                <h4>Chat</h4>
                <p>Pilih pengguna untuk memulai chat</p>
              </div>
            </div>
            <button class="close-button" @click="closeChat()">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="chat-messages" ref="messagesContainer">
            <template x-for="message in messages" :key="message.id">
              <div class="chat-message" :class="message.sent ? 'sent' : 'received'">
                <img class="avatar" :src="message.avatar || '/img/default-avatar.png'" :alt="message.sender">
                <div class="message-content">
                  <div class="message-bubble" x-text="message.content"></div>
                  <div class="message-time" x-text="message.time"></div>
                </div>
              </div>
            </template>
            <div x-show="messages.length === 0" class="text-center py-8 text-gray-500">
              <p>Belum ada pesan. Mulai percakapan!</p>
            </div>
          </div>
          
          <div class="chat-input">
            <input type="text" x-model="newMessage" @keyup.enter="sendMessage()" placeholder="Ketik pesan...">
            <button @click="sendMessage()">
              <i class="fas fa-paper-plane"></i>
            </button>
          </div>
        </div>
      </div>
      
      <!-- Zoom Control -->
      <div class="zoom-control" x-data="{ zoomOpen: false }">
        <button @click="zoomOpen = !zoomOpen" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
          <i class="fas fa-search-minus text-gray-600 dark:text-gray-300"></i>
        </button>
        
        <!-- Zoom Dropdown -->
        <div x-show="zoomOpen" 
             @click.away="zoomOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="zoom-dropdown">
          <div class="zoom-option" :class="{ 'active': zoomLevel === 1 }" @click="setZoomLevel(1)">
            <i class="fas fa-compress"></i>
            <span>100% Normal</span>
          </div>
          <div class="zoom-option" :class="{ 'active': zoomLevel === 0.8 }" @click="setZoomLevel(0.8)">
            <i class="fas fa-search-minus"></i>
            <span>80% Zoom Out</span>
          </div>
        </div>
      </div>
      
      <!-- Dark Mode Toggle -->
      <button @click="toggleDarkMode()" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
        <i class="fas fa-moon text-gray-600 dark:text-gray-300" x-show="!darkMode"></i>
        <i class="fas fa-sun text-gray-600 dark:text-gray-300" x-show="darkMode"></i>
      </button>
      
      <!-- Notifications -->
      <div class="relative" x-data="{ notificationOpen: false }">
        <button @click="notificationOpen = !notificationOpen" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 relative">
          <i class="fas fa-bell text-gray-600 dark:text-gray-300"></i>
          <span class="notification-badge" x-show="unreadCount > 0" x-text="unreadCount"></span>
        </button>
        
        <!-- Notification Dropdown -->
        <div x-show="notificationOpen" 
             @click.away="notificationOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 overflow-hidden">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-medium text-gray-900 dark:text-white">Notifikasi</h3>
          </div>
          <div class="max-h-64 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
              <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 border-b border-gray-100 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="notification.title"></p>
                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="notification.message"></p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="notification.time"></p>
              </div>
            </template>
            <div x-show="notifications.length === 0" class="p-4 text-center text-gray-500 dark:text-gray-400">
              <p>Tidak ada notifikasi baru</p>
            </div>
          </div>
          <div class="p-2 border-t border-gray-200 dark:border-gray-700">
            <a href="#" class="block p-2 text-center text-sm text-blue-600 hover:text-blue-800">Lihat semua notifikasi</a>
          </div>
        </div>
      </div>
      
      <!-- User Profile -->
      <div class="relative" x-data="{ profileOpen: false }">
        <button @click="profileOpen = !profileOpen" class="flex items-center p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
          @if(auth()->user()->profile && auth()->user()->profile->foto)
            <img src="{{ asset('storage/profiles/' . auth()->user()->profile->foto) }}" alt="Profile" class="w-8 h-8 rounded-full">
          @else
            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
              <span class="text-white font-bold text-sm">{{ substr(auth()->user()->nama_lengkap, 0, 1) }}</span>
            </div>
          @endif
          <i class="fas fa-chevron-down text-xs text-gray-600 dark:text-gray-300 ml-1"></i>
        </button>
        
        <!-- Profile Dropdown -->
        <div x-show="profileOpen" 
             @click.away="profileOpen = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg z-50 overflow-hidden">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->nama_lengkap }}</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst(auth()->user()->role) }}</p>
          </div>
          <div class="py-1">
            <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
              <i class="fas fa-user mr-2"></i> Profil Saya
            </a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
              <i class="fas fa-cog mr-2"></i> Pengaturan
            </a>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </header>
  

    <div id="app">
        @include('partials.navigation')
  <!-- Main Content -->
  <main class="main-content" :style="`zoom: ${zoomLevel}`">
    <div class="page-content" id="page-content">
      @yield('content')
    </div>
  </main>
  
  @include('partials.footer')
 </div>

  <!-- JavaScript -->
    <script>
    function layoutApp() {
      return {
        sidebarOpen: false,
        darkMode: false,
        pageTitle: 'Dashboard',
        pageSubtitle: 'Sistem Informasi Arsip Soeradji',
        notifications: [],
        unreadCount: 0,
        zoomLevel: 1,
        submenus: {
          'registrasi-arsip': false,
          'surat-tugas': false,
          'watermark': false,
          'berita-acara': false,
          'laporan': false,
          'pengaturan': false
        },
        
        init() {
          this.checkDarkMode();
          this.loadNotifications();
          this.setActiveMenu();
          this.loadZoomPreference();
          this.updatePageInfo();
          this.setViewportHeight(); // TAMBAHAN: Set tinggi viewport saat inisialisasi

          // TAMBAHAN: Set ulang tinggi viewport saat jendela di-resize
          window.addEventListener('resize', () => {
            this.setViewportHeight();
          });
        },
        
        // Perbarui fungsi setViewportHeight
        setViewportHeight() {
          const currentZoom = this.zoomLevel;
          
          if (currentZoom < 1) { // Hanya jika zoom out
            // Tinggi body harus dibagi dengan level zoom agar scroll area mencukupi
            const newBodyHeight = (window.innerHeight / currentZoom);
            document.body.style.height = `${newBodyHeight}px`;
            
            // Atur tinggi sidebar juga
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
              sidebar.style.height = `${newBodyHeight}px`;
            }
            
            // Atur tinggi main content juga
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
              const newMainContentHeight = (newBodyHeight - 64); // 64px adalah header height
              mainContent.style.minHeight = `${newMainContentHeight}px`;
            }
          } else {
            // Kembalikan ke normal jika zoom 100% atau lebih
            document.body.style.height = '100vh';
            
            // Kembalikan tinggi sidebar
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
              sidebar.style.height = '100vh';
            }
            
            // Kembalikan tinggi main content
            const mainContent = document.querySelector('.main-content');
            if (mainContent) {
              mainContent.style.minHeight = 'calc(100vh - var(--header-height))';
            }
          }
        },
        
        toggleSidebar() {
          this.sidebarOpen = !this.sidebarOpen;
        },
        
        toggleDarkMode() {
          this.darkMode = !this.darkMode;
          localStorage.setItem('darkMode', this.darkMode);
          
          if (this.darkMode) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        },
        
        checkDarkMode() {
          this.darkMode = localStorage.getItem('darkMode') === 'true';
          
          if (this.darkMode) {
            document.documentElement.classList.add('dark');
          } else {
            document.documentElement.classList.remove('dark');
          }
        },
        
        setZoomLevel(level) {
          this.zoomLevel = level;
          document.documentElement.style.setProperty('--zoom-level', level);
          localStorage.setItem('zoomLevel', level);
          this.setViewportHeight(); // DIUBAH: Panggil fungsi ini setiap kali zoom berubah
        },
        
        toggleSubmenu(menuId) {
          this.submenus[menuId] = !this.submenus[menuId];
        },
        
        loadNotifications() {
          fetch('/api/notifications')
            .then(response => response.json())
            .then(data => {
              this.notifications = data.notifications;
              this.unreadCount = data.unread_count;
            })
            .catch(error => {
              console.error('Error loading notifications:', error);
            });
        },
        
        setActiveMenu() {
          const currentPath = window.location.pathname;
          const menuItems = document.querySelectorAll('.menu-item, .submenu-item');
          
          menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath.includes(href)) {
              item.classList.add('active');
              
              const submenu = item.closest('.submenu');
              if (submenu) {
                const menuId = submenu.previousElementSibling.getAttribute('onclick').match(/'([^']+)'/)[1];
                this.submenus[menuId] = true;
              }
            }
          });
        },
        
        updatePageInfo() {
          const currentPath = window.location.pathname;
          
          if (currentPath.includes('/dashboard')) {
            this.pageTitle = 'Dashboard';
            this.pageSubtitle = 'Sistem Informasi Arsip Soeradji';
          } else if (currentPath.includes('/arsip/aktif')) {
            this.pageTitle = 'Arsip Aktif';
            this.pageSubtitle = 'Registrasi Arsip Aktif';
          } else if (currentPath.includes('/arsip/inaktif')) {
            this.pageTitle = 'Arsip Inaktif';
            this.pageSubtitle = 'Registrasi Arsip Inaktif';
          } else if (currentPath.includes('/arsip/vital')) {
            this.pageTitle = 'Arsip Vital';
            this.pageSubtitle = 'Registrasi Arsip Vital';
          } else if (currentPath.includes('/arsip/alihmedia')) {
            this.pageTitle = 'Arsip Alih Media';
            this.pageSubtitle = 'Registrasi Arsip Alih Media';
          } else if (currentPath.includes('/surat_tugas')) {
            this.pageTitle = 'Surat Tugas';
            this.pageSubtitle = 'Pembuatan Surat Tugas';
          } else if (currentPath.includes('/watermark')) {
            this.pageTitle = 'Watermark';
            this.pageSubtitle = 'Pengaturan Watermark';
          } else if (currentPath.includes('/berita_acara')) {
            this.pageTitle = 'Berita Acara';
            this.pageSubtitle = 'Pembuatan Berita Acara';
          } else if (currentPath.includes('/laporan')) {
            this.pageTitle = 'Laporan';
            this.pageSubtitle = 'Laporan Kearsipan';
          } else if (currentPath.includes('/pengaturan')) {
            this.pageTitle = 'Pengaturan';
            this.pageSubtitle = 'Pengaturan Sistem';
          }
        },
        
        navigateToPage(event, url) {
          event.preventDefault();
          
          const pageContent = document.getElementById('page-content');
          pageContent.classList.add('loading');
          
          fetch(url)
            .then(response => response.text())
            .then(html => {
              const parser = new DOMParser();
              const doc = parser.parseFromString(html, 'text/html');
              const newContent = doc.getElementById('page-content').innerHTML;
              
              pageContent.innerHTML = newContent;
              history.pushState({}, '', url);
              this.setActiveMenu();
              this.updatePageInfo();
              pageContent.classList.remove('loading');
            })
            .catch(error => {
              console.error('Error loading page:', error);
              pageContent.classList.remove('loading');
              
              pageContent.innerHTML = `
                <div class="card">
                  <div class="flex items-center justify-center h-64">
                    <div class="text-center">
                      <i class="fas fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                      <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Terjadi Kesalahan</h3>
                      <p class="text-gray-600 dark:text-gray-400">Gagal memuat halaman. Silakan coba lagi.</p>
                      <button @click="window.location.reload()" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Muat Ulang
                      </button>
                    </div>
                  </div>
                </div>
              `;
            });
        }
      }
    }
    
    function chatWidget() {
      return {
        chatOpen: false,
        showUserSelection: false,
        selectedUser: null,
        onlineUsers: [],
        messages: [],
        newMessage: '',
        unreadMessages: 0,
        
        init() {
          this.loadOnlineUsers();
          this.checkDarkMode();
          this.loadNotifications();
          this.setActiveMenu();
          this.loadZoomPreference();
          this.updatePageInfo();
          this.setViewportHeight(); // Panggil di sini
          window.addEventListener('resize', () => {
          this.setViewportHeight(); // Panggil di sini
          setInterval(() => {
          this.checkNewMessages();
          })}, 30000);
        },
        
        toggleChatUserSelection() {
          this.showUserSelection = !this.showUserSelection;
          this.chatOpen = false;
        },
        
        closeChat() {
          this.chatOpen = false;
          this.selectedUser = null;
        },
        
        openChatWithUser(user) {
          this.selectedUser = user;
          this.showUserSelection = false;
          this.chatOpen = true;
          this.loadMessages(user.id);
        },
        
        loadOnlineUsers() {
          fetch('/api/users/online')
            .then(response => response.json())
            .then(data => {
              this.onlineUsers = data;
            })
            .catch(error => {
              console.error('Error loading online users:', error);
            });
        },
        
        loadMessages(userId) {
          fetch(`/api/chat/messages/${userId}`)
            .then(response => response.json())
            .then(data => {
              this.messages = data;
              
              this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                container.scrollTop = container.scrollHeight;
              });
            })
            .catch(error => {
              console.error('Error loading messages:', error);
            });
        },
        
        sendMessage() {
          if (!this.newMessage.trim() || !this.selectedUser) return;
          
          const message = {
            id: Date.now(),
            content: this.newMessage,
            sent: true,
            sender: 'You',
            avatar: '/img/default-avatar.png',
            time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })
          };
          
          this.messages.push(message);
          this.newMessage = '';
          
          // Simulate sending to server
          fetch(`/api/chat/send/${this.selectedUser.id}`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: message.content })
          })
          .then(response => response.json())
          .then(data => {
            // Handle response
          })
          .catch(error => {
            console.error('Error sending message:', error);
          });
          
          this.$nextTick(() => {
            const container = this.$refs.messagesContainer;
            container.scrollTop = container.scrollHeight;
          });
        },
        
        checkNewMessages() {
          fetch('/api/chat/unread')
            .then(response => response.json())
            .then(data => {
              this.unreadMessages = data.count;
            })
            .catch(error => {
              console.error('Error checking new messages:', error);
            });
        }
      }
    }
  </script>
  
  @stack('scripts')
</body>
</html> 