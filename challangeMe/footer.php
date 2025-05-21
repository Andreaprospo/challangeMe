  <!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="CSS/styleFooter.css">
      <title>Document</title>
  </head>
  <body>
      
      <footer>
          <div><a href="home.php">Home</a></div>
          <div><a href="profilo.php">Profilo</a></div>
          <div><a href="chat.php">Chat</a></div>
      </footer>
  </body>
  <script>
      // Footer functionality
  document.addEventListener("DOMContentLoaded", function() {
    // Set active class on current page link
    const currentPage = window.location.pathname.split('/').pop();
    const footerLinks = document.querySelectorAll('footer a');
    
    footerLinks.forEach(link => {
      const linkHref = link.getAttribute('href');
      if (linkHref === currentPage) {
        link.classList.add('active');
      }
    });
    
    // Hide footer when scrolling down, show when scrolling up
    let lastScrollTop = 0;
    const footer = document.querySelector('footer');
    
    window.addEventListener('scroll', function() {
      let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      if (scrollTop > lastScrollTop && scrollTop > 200) {
        // Scrolling down & not at the top
        footer.classList.add('hidden');
      } else {
        // Scrolling up or at the top
        footer.classList.remove('hidden');
      }
      
      lastScrollTop = scrollTop;
    });
    
    // Optional: Add notification badges with JavaScript
    // This is an example that you can use if needed
    function addNotificationBadge(linkHref, count) {
      const link = document.querySelector(`footer a[href="${linkHref}"]`);
      if (link && count > 0) {
        const badge = document.createElement('span');
        badge.className = 'notification-badge';
        badge.textContent = count > 9 ? '9+' : count;
        link.parentNode.appendChild(badge);
      }
    }
    
    // Example usage:
    // addNotificationBadge('chat.php', 3);
    
    // Optional: Add tactile feedback on mobile (requires a vibration API)
    footerLinks.forEach(link => {
      link.addEventListener('click', function() {
        if (navigator.vibrate) {
          navigator.vibrate(30);
        }
      });
    });
  });
  </script>
  </html>