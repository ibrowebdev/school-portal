import Swal from "sweetalert2";
import axios from "axios";

window.Swal = Swal;

if (typeof $ !== 'undefined') {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
}

document.addEventListener("submit", function (e) {
    if (e.target && e.target.classList.contains("x-submit")) {
        e.preventDefault();

        const form = e.target;
        
        const executeRequest = () => {
            const errorContainer = document.getElementById('form-errors-container');
            if (errorContainer) {
                errorContainer.classList.add('hidden');
            }

            const submitBtn = form.querySelector('button[type="submit"], button:not([type="button"]), input[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.innerHTML : "";
            const dataThen = form.getAttribute("data-then");

            // Show roller/loader (Tailwind spin class or inline spinner SVG)
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add("cursor-not-allowed", "opacity-75");
                submitBtn.innerHTML = `
                    <span class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-current flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        ${originalBtnText}
                    </span>
                `;
            }

            const formData = new FormData(form);

            axios({
                method: form.method || "post",
                url: form.action,
                data: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            })
                .then((response) => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove("cursor-not-allowed", "opacity-75");
                        submitBtn.innerHTML = originalBtnText;
                    }

                    const message =
                        response.data.message ||
                        response.data.success ||
                        "Action completed successfully!";

                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        icon: "success",
                        text: message,
                        background: "#f4f4f5", // zinc-900 background for sweetalert
                        color: "#18181b", // zinc-100 text
                        iconColor: "#10b981", // emerald-500
                    });

                    if (response.data.redirect) {
                        setTimeout(() => {
                            window.location.href = response.data.redirect;
                        }, 1000);
                    } else if (dataThen && dataThen.trim() === "reload") {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }else if(dataThen && dataThen.startsWith("redirect:")) {
                        setTimeout(() => {
                            window.location.href = dataThen.replace("redirect:", "");
                        }, 1000);
                    } 
                    else {
                        form.reset();
                    }
                })
                .catch((error) => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove("cursor-not-allowed", "opacity-75");
                        submitBtn.innerHTML = originalBtnText;
                    }

                    let errorMessage = "Something went wrong. Please try again.";
                    let allErrors = [];

                    if (error.response && error.response.data) {
                        if (error.response.data.errors) {
                            let errors = error.response.data.errors;
                            for (let key in errors) {
                                allErrors.push(...errors[key]);
                            }
                            
                            // Extract first validation error for toast
                            let firstErrorKey = Object.keys(errors)[0];
                            errorMessage = errors[firstErrorKey][0];
                        } else if (error.response.data.message) {
                            errorMessage = error.response.data.message;
                            allErrors.push(errorMessage);
                        }
                    }

                    const errorContainer = document.getElementById('form-errors-container');
                    const errorList = document.getElementById('form-errors-list');
                    if (errorContainer && errorList && allErrors.length > 0) {
                        errorList.innerHTML = '';
                        allErrors.forEach(err => {
                            let li = document.createElement('li');
                            li.textContent = err;
                            errorList.appendChild(li);
                        });
                        errorContainer.classList.remove('hidden');
                        
                        // Scroll to the error container
                        errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }

                    Swal.fire({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        icon: "error",
            
                        text: errorMessage,
                        background: "#f4f4f5",
                        color: "#18181b",
                        iconColor: "#ef4444", // red-500
                    });
                });
        };

        const confirmMessage = form.getAttribute("data-confirm");
        
        if (confirmMessage) {
            const confirmText = form.getAttribute("data-confirm-text") || "Confirm";
            const confirmColor = form.getAttribute("data-confirm-color") || "#3b82f6";
            
            Swal.fire({
                text: confirmMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#d33',
                confirmButtonText: confirmText,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    executeRequest();
                }
            });
        } else {
            executeRequest();
        }
    }
});
