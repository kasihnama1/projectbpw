// Main JavaScript for LPK Fujisan Plus

// Import Bootstrap
const bootstrap = window.bootstrap

document.addEventListener("DOMContentLoaded", () => {
  // Initialize tooltips
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl))

  // Initialize popovers
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  var popoverList = popoverTriggerList.map((popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl))

  // Auto-hide success alerts after 5 seconds (but keep error alerts)
  setTimeout(() => {
    var successAlerts = document.querySelectorAll(".alert-success")
    successAlerts.forEach((alert) => {
      var bsAlert = new bootstrap.Alert(alert)
      bsAlert.close()
    })
  }, 5000)

  // Keep error alerts visible longer (8 seconds)
  setTimeout(() => {
    var errorAlerts = document.querySelectorAll(".alert-danger")
    errorAlerts.forEach((alert) => {
      var bsAlert = new bootstrap.Alert(alert)
      bsAlert.close()
    })
  }, 8000)

  // Form validation
  var forms = document.querySelectorAll(".needs-validation")
  Array.prototype.slice.call(forms).forEach((form) => {
    form.addEventListener(
      "submit",
      (event) => {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }
        form.classList.add("was-validated")
      },
      false,
    )
  })

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault()
      const target = document.querySelector(this.getAttribute("href"))
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start",
        })
      }
    })
  })

  // Loading state for forms
  document.querySelectorAll("form").forEach((form) => {
    form.addEventListener("submit", () => {
      const submitBtn = form.querySelector('button[type="submit"]')
      if (submitBtn) {
        submitBtn.disabled = true
        const originalText = submitBtn.innerHTML
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...'

        // Re-enable button after 3 seconds (in case of error)
        setTimeout(() => {
          submitBtn.disabled = false
          submitBtn.innerHTML = originalText
        }, 3000)
      }
    })
  })

  // Image preview for file uploads
  document.querySelectorAll('input[type="file"]').forEach((input) => {
    input.addEventListener("change", (e) => {
      const file = e.target.files[0]
      if (file && file.type.startsWith("image/")) {
        const reader = new FileReader()
        reader.onload = (e) => {
          let preview = document.getElementById("image-preview")
          if (!preview) {
            preview = document.createElement("img")
            preview.id = "image-preview"
            preview.className = "img-thumbnail mt-2"
            preview.style.maxWidth = "200px"
            input.parentNode.appendChild(preview)
          }
          preview.src = e.target.result
        }
        reader.readAsDataURL(file)
      }
    })
  })

  // Confirmation dialogs
  document.querySelectorAll("[data-confirm]").forEach((element) => {
    element.addEventListener("click", function (e) {
      const message = this.getAttribute("data-confirm")
      if (!confirm(message)) {
        e.preventDefault()
      }
    })
  })

  // Auto-refresh for admin dashboard (every 5 minutes)
  if (window.location.href.includes("admin_dashboard")) {
    setInterval(() => {
      // Only refresh if user is still active (not idle)
      if (document.hasFocus()) {
        location.reload()
      }
    }, 300000) // 5 minutes
  }

  // Search functionality
  const searchInput = document.getElementById("search")
  if (searchInput) {
    searchInput.addEventListener("input", function () {
      const searchTerm = this.value.toLowerCase()
      const searchableElements = document.querySelectorAll("[data-searchable]")

      searchableElements.forEach((element) => {
        const text = element.textContent.toLowerCase()
        const parent = element.closest("tr, .card, .list-group-item")
        if (parent) {
          if (text.includes(searchTerm)) {
            parent.style.display = ""
          } else {
            parent.style.display = "none"
          }
        }
      })
    })
  }

  // Copy to clipboard functionality
  document.querySelectorAll("[data-copy]").forEach((element) => {
    element.addEventListener("click", function () {
      const text = this.getAttribute("data-copy")
      navigator.clipboard.writeText(text).then(() => {
        // Show success message
        showNotification("Copied to clipboard!", "success")
      })
    })
  })

  // Dynamic table sorting
  document.querySelectorAll("th[data-sort]").forEach((header) => {
    header.style.cursor = "pointer"
    header.innerHTML += ' <i class="fas fa-sort text-muted"></i>'

    header.addEventListener("click", function () {
      const table = this.closest("table")
      const tbody = table.querySelector("tbody")
      const rows = Array.from(tbody.querySelectorAll("tr"))
      const column = this.cellIndex
      const isAscending = this.classList.contains("sort-asc")

      // Remove existing sort classes
      table.querySelectorAll("th").forEach((th) => {
        th.classList.remove("sort-asc", "sort-desc")
        th.querySelector("i").className = "fas fa-sort text-muted"
      })

      // Sort rows
      rows.sort((a, b) => {
        const aVal = a.cells[column].textContent.trim()
        const bVal = b.cells[column].textContent.trim()

        if (isAscending) {
          return bVal.localeCompare(aVal)
        } else {
          return aVal.localeCompare(bVal)
        }
      })

      // Update table
      rows.forEach((row) => tbody.appendChild(row))

      // Update sort indicator
      if (isAscending) {
        this.classList.add("sort-desc")
        this.querySelector("i").className = "fas fa-sort-down text-primary"
      } else {
        this.classList.add("sort-asc")
        this.querySelector("i").className = "fas fa-sort-up text-primary"
      }
    })
  })

  // Auto-save for forms (draft functionality)
  document.querySelectorAll("form[data-autosave]").forEach((form) => {
    const formId = form.getAttribute("data-autosave")

    // Load saved data
    const savedData = localStorage.getItem("form_" + formId)
    if (savedData) {
      const data = JSON.parse(savedData)
      Object.keys(data).forEach((key) => {
        const input = form.querySelector(`[name="${key}"]`)
        if (input) {
          input.value = data[key]
        }
      })
    }

    // Save data on input
    form.addEventListener("input", () => {
      const formData = new FormData(form)
      const data = {}
      for (const [key, value] of formData.entries()) {
        data[key] = value
      }
      localStorage.setItem("form_" + formId, JSON.stringify(data))
    })

    // Clear saved data on successful submit
    form.addEventListener("submit", () => {
      localStorage.removeItem("form_" + formId)
    })
  })
})

// Utility functions
function formatCurrency(amount) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(amount)
}

function formatDate(dateString) {
  return new Intl.DateTimeFormat("id-ID", {
    year: "numeric",
    month: "long",
    day: "numeric",
  }).format(new Date(dateString))
}

function showNotification(message, type = "info") {
  const toast = document.createElement("div")
  toast.className = `toast position-fixed top-0 end-0 m-3`
  toast.innerHTML = `
        <div class="toast-body bg-${type} text-white">
            <i class="fas fa-${type === "success" ? "check" : type === "error" ? "times" : "info"} me-2"></i>
            ${message}
        </div>
    `
  document.body.appendChild(toast)

  const bsToast = new bootstrap.Toast(toast)
  bsToast.show()

  setTimeout(() => {
    toast.remove()
  }, 5000)
}

// Export functions for global use
window.LPKFujisan = {
  formatCurrency,
  formatDate,
  showNotification,
}
