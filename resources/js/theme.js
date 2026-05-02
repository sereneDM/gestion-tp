// Initialize theme on page load
function initTheme() {
  const html = document.documentElement;
  const savedTheme = localStorage.getItem('theme') || 'dark';
  
  if (savedTheme === 'dark') {
    html.classList.add('dark');
    html.setAttribute('data-theme', 'dark');
  } else {
    html.classList.remove('dark');
    html.setAttribute('data-theme', 'light');
  }
  
  updateThemeIcon(savedTheme);
}

// Toggle theme
function toggleTheme() {
  const html = document.documentElement;
  const currentTheme = localStorage.getItem('theme') || 'dark';
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  
  // Update DOM
  if (newTheme === 'dark') {
    html.classList.add('dark');
    html.setAttribute('data-theme', 'dark');
  } else {
    html.classList.remove('dark');
    html.setAttribute('data-theme', 'light');
  }
  
  // Save preference
  localStorage.setItem('theme', newTheme);
  updateThemeIcon(newTheme);
}

// Update icon based on theme
function updateThemeIcon(theme) {
  const sunIcon = document.getElementById('light-icon');
  const moonIcon = document.getElementById('dark-icon');
  
  if (sunIcon && moonIcon) {
    if (theme === 'dark') {
      sunIcon.classList.remove('hidden');
      moonIcon.classList.add('hidden');
    } else {
      sunIcon.classList.add('hidden');
      moonIcon.classList.remove('hidden');
    }
  }
}

// Initialize theme when DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTheme);
} else {
  initTheme();
}

// Export functions for use in other modules
window.toggleTheme = toggleTheme;
