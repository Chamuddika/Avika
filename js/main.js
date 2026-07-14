document.addEventListener("DOMContentLoaded", function () {
  // --- Navbar Scroll Effect ---
  const navbar = document.querySelector(".navbar-custom");
  if (navbar && !navbar.classList.contains("navbar-light-bg")) {
    window.addEventListener("scroll", function () {
      if (window.scrollY > 50) {
        navbar.classList.add("scrolled");
      } else {
        navbar.classList.remove("scrolled");
      }
    });
  }

  // --- 2D Scroll Reveal Animation ---
  const reveals = document.querySelectorAll(".reveal");

  if (reveals.length > 0) {
    const revealOnScroll = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("active");
            observer.unobserve(entry.target); // Stop observing once animated
          }
        });
      },
      {
        threshold: 0.1, // Trigger when 10% of the element is visible
      },
    );

    reveals.forEach((el) => {
      // Check if the element is ALREADY in the viewport when the page loads
      const rect = el.getBoundingClientRect();
      const isInViewport =
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <=
          (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <=
          (window.innerWidth || document.documentElement.clientWidth);

      if (isInViewport) {
        // If it's already visible on load, add active class immediately
        el.classList.add("active");
      } else {
        // Otherwise, let the observer watch it
        revealOnScroll.observe(el);
      }
    });
  }

  // --- Add to Cart Animation & Toast ---
  const toastContainer = document.createElement("div");
  toastContainer.style.cssText =
    "position:fixed;top:20px;right:20px;z-index:9999;";
  document.body.appendChild(toastContainer);

  function showToast(message) {
    const toast = document.createElement("div");
    toast.style.cssText = `
            background-color:var(--primary); color:white; padding:16px 24px; border-radius:8px;
            margin-bottom:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); font-family:'Lato',sans-serif;
            transform:translateX(120%); transition:transform 0.4s ease;
        `;
    toast.textContent = message;
    toastContainer.appendChild(toast);

    // Trigger animation
    setTimeout(() => (toast.style.transform = "translateX(0)"), 50);

    // Remove after 3 seconds
    setTimeout(() => {
      toast.style.transform = "translateX(120%)";
      setTimeout(() => toast.remove(), 400);
    }, 3000);
  }

  // --- Password Toggle (Auth Pages) ---
  document.querySelectorAll(".password-toggle").forEach((btn) => {
    btn.addEventListener("click", function () {
      const input = this.previousElementSibling;
      const icon = this.querySelector("i");
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
      }
    });
  });

  // // --- Auth Form Validation Simulation ---
  // document.querySelectorAll('.auth-form').forEach(form => {
  //     form.addEventListener('submit', function(e) {
  //         e.preventDefault();
  //         showToast('Processing request...');
  //     });
  // });
});

// ============================================
// SINGLE PRODUCT PAGE FUNCTIONS
// ============================================


// 4. Interactive Star Rating (Review Form)
const starRatingInput = document.querySelector(".star-rating-input");
if (starRatingInput) {
  const stars = starRatingInput.querySelectorAll("i");
  const ratingInput = document.getElementById("ratingValue");

  stars.forEach((star) => {
    // Hover effect
    star.addEventListener("mouseenter", function () {
      const rating = this.getAttribute("data-rating");
      stars.forEach((s) => {
        if (s.getAttribute("data-rating") <= rating) {
          s.classList.add("hover-active");
        } else {
          s.classList.remove("hover-active");
        }
      });
    });

    // Remove hover effect
    star.addEventListener("mouseleave", function () {
      stars.forEach((s) => s.classList.remove("hover-active"));
    });

    // Click to set rating
    star.addEventListener("click", function () {
      const rating = this.getAttribute("data-rating");
      ratingInput.value = rating;
      stars.forEach((s) => {
        if (s.getAttribute("data-rating") <= rating) {
          s.classList.add("active");
          s.classList.remove("bi-star");
          s.classList.add("bi-star-fill");
        } else {
          s.classList.remove("active");
          s.classList.add("bi-star");
          s.classList.remove("bi-star-fill");
        }
      });
    });
  });
}

// 5. Review Form Submission
const reviewForm = document.getElementById("reviewForm");
if (reviewForm) {
  reviewForm.addEventListener("submit", function (e) {
    e.preventDefault();
    const rating = document.getElementById("ratingValue").value;
    if (rating === "0") {
      showToast("Please select a star rating.");
      return;
    }
    showToast("Thank you for your review!");
    reviewForm.reset();
    // Reset stars visually
    document.querySelectorAll(".star-rating-input i").forEach((s) => {
      s.classList.remove("active");
      s.classList.remove("bi-star-fill");
      s.classList.add("bi-star");
    });
    document.getElementById("ratingValue").value = "0";
  });
}

//  Modal Interactive Star Rating Logic
const modalStarRating = document.querySelector(".modal-star-rating");
if (modalStarRating) {
  const modalStars = modalStarRating.querySelectorAll("i");
  const modalRatingInput = document.getElementById("modalRatingValue");

  modalStars.forEach((star) => {
    star.addEventListener("mouseenter", function () {
      const rating = this.getAttribute("data-rating");
      modalStars.forEach((s) => {
        s.classList.toggle(
          "hover-active",
          s.getAttribute("data-rating") <= rating,
        );
      });
    });

    star.addEventListener("mouseleave", function () {
      modalStars.forEach((s) => s.classList.remove("hover-active"));
    });

    star.addEventListener("click", function () {
      const rating = this.getAttribute("data-rating");
      modalRatingInput.value = rating;
      modalStars.forEach((s) => {
        if (s.getAttribute("data-rating") <= rating) {
          s.classList.add("active");
          s.classList.remove("bi-star");
          s.classList.add("bi-star-fill");
        } else {
          s.classList.remove("active");
          s.classList.add("bi-star");
          s.classList.remove("bi-star-fill");
        }
      });
    });
  });
}

function resetModalStars() {
  const modalStars = document.querySelectorAll(".modal-star-rating i");
  modalStars.forEach((s) => {
    s.classList.remove("active", "hover-active");
    s.classList.remove("bi-star-fill");
    s.classList.add("bi-star");
  });
}

//  Print Invoice Function
function printInvoice() {
  window.print();
}

document.addEventListener('DOMContentLoaded', function() {

    // --- Sidebar Toggle for Mobile ---
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('adminSidebar');
    
    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function() {
            adminSidebar.classList.toggle('active');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(e) {
        if (adminSidebar && adminSidebar.classList.contains('active')) {
            if (!adminSidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                adminSidebar.classList.remove('active');
            }
        }
    });

    // --- Toast Notification System for Admin ---
    window.showAdminToast = function(message, type = 'success') {
        let container = document.querySelector('.admin-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'admin-toast-container';
            container.style.cssText = 'position:fixed;top:20px;right:20px;z-index:9999;';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const bgColor = type === 'success' ? '#4a7c59' : '#dc3545';
        toast.style.cssText = `
            background-color:${bgColor}; color:white; padding:16px 24px; border-radius:8px;
            margin-bottom:10px; box-shadow:0 4px 15px rgba(0,0,0,0.2); font-family:'Lato',sans-serif;
            transform:translateX(120%); transition:transform 0.4s ease; display:flex; align-items:center; gap:10px;
        `;
        toast.innerHTML = `<i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i> ${message}`;
        container.appendChild(toast);
        
        setTimeout(() => toast.style.transform = 'translateX(0)', 50);
        setTimeout(() => {
            toast.style.transform = 'translateX(120%)';
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    };

    // // --- Delete Buttons ---
    // document.querySelectorAll('.btn-delete').forEach(btn => {
    //     btn.addEventListener('click', function() {
    //         if(confirm('Are you sure you want to delete this item?')) {
    //             this.closest('tr').remove();
    //             showAdminToast('Item deleted successfully!');
    //         }
    //     });
    // });

    // // --- Order Status Change ---
    // document.querySelectorAll('.admin-table select').forEach(select => {
    //     select.addEventListener('change', function() {
    //         showAdminToast(`Order status updated to ${this.value}`);
    //     });
    // });

});
