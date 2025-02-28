document.addEventListener("DOMContentLoaded", function () {
    let cropper;
    let errorMessages = [];

    const imageInput = document.getElementById("photoLink");
    const photoPreview = document.getElementById("photoPreview");
    const cropModal = new bootstrap.Modal(document.getElementById("cropperModal"));
    const cropSave = document.getElementById("cropSave");
    const form = document.getElementById("profileForm");

    imageInput.addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement("img");
                img.src = e.target.result;
                img.id = "cropImage";
                img.style.maxWidth = "100%";

                let modalBody = document.querySelector("#cropperModal .modal-body");
                modalBody.innerHTML = "";
                modalBody.appendChild(img);

                cropper = new Cropper(img, {
                    aspectRatio: 1,
                    viewMode: 2,
                    minContainerWidth: 250,
                    minContainerHeight: 250,
                });

                cropModal.show();
            };
            reader.readAsDataURL(file);
        }
    });

    cropSave.addEventListener("click", function () {
        if (cropper) {
            let canvas = cropper.getCroppedCanvas({ width: 200, height: 200 });
            canvas.toBlob((blob) => {
                let url = URL.createObjectURL(blob);
                photoPreview.src = url;
                let croppedFile = new File([blob], "profile.jpg", { type: "image/jpeg" });
                let dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                imageInput.files = dataTransfer.files;
            });
            cropModal.hide();
        }
    });

    form.addEventListener("submit", function (event) {
        const emailInput = document.getElementById("userEmail");
        const passwordInput = document.getElementById("userPassword");
        const usernameInput = document.getElementById("userName");
        const firstNameInput = document.getElementById("firstName");
        const lastNameInput = document.getElementById("lastName");
        const fileInput = document.getElementById("photoLink");

        errorMessages = [];
        document.querySelector(".alert-container").innerHTML = '';
        document.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));

        validationEmail(emailInput);
        validationUsername(usernameInput);
        validationPassword(passwordInput);
        validationLastAndFirstName(firstNameInput, lastNameInput);
        validationFile(fileInput);

        if (errorMessages.length > 0) {
            event.preventDefault();
            displayErrorMessages(errorMessages);
        }
    });

    function validationEmail(emailInput) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        if (emailInput && emailInput.value.trim() === "") {
            emailInput.classList.add("is-invalid");
            errorMessages.push("Email is required");
        } else if (emailInput && !regex.test(emailInput.value)) {
            emailInput.classList.add("is-invalid");
            errorMessages.push("Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        }
    }

    function validationPassword(passwordInput) {
        const regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;

        if (passwordInput && passwordInput.value.length < 8) {
            passwordInput.classList.add("is-invalid");
            errorMessages.push("Password must be at least 8 characters.");
        } else if (passwordInput && !regex.test(passwordInput.value)) {
            passwordInput.classList.add("is-invalid");
            errorMessages.push("Password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.");
        }
    }

    function validationUsername(usernameInput) {
        const usernameLength = usernameInput.value.trim().length;

        if (usernameLength < 3) {
            usernameInput.classList.add("is-invalid");
            errorMessages.push("Username must be at least 3 characters long.");
        } else if (usernameLength > 50) {
            usernameInput.classList.add("is-invalid");
            errorMessages.push("Username cannot exceed 50 characters.");
        }
    }

    function validationLastAndFirstName(firstNameInput, lastNameInput) {
        if (firstNameInput && firstNameInput.value.length > 50) {
            firstNameInput.classList.add("is-invalid");
            errorMessages.push("First name cannot exceed 50 characters.");
        }

        if (lastNameInput && lastNameInput.value.length > 50) {
            lastNameInput.classList.add("is-invalid");
            errorMessages.push("Last name cannot exceed 50 characters.");
        }
    }

    function validationFile(fileInput) {
        if (fileInput && fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!allowedTypes.includes(file.type)) {
                errorMessages.push(fileInput, "Only JPEG, PNG, and GIF images are allowed.");
            }
        }
    }

    function displayErrorMessages(errors) {
        let alertContainer = document.querySelector(".alert-container");

        let errorHtml = "";
        if (errors.length > 0) {
            errorHtml += `
                <div class="alert alert-danger">
                    <ul>
                        ${errors.map(error => `<li>${error}</li>`).join("")}
                    </ul>
                </div>
            `;
        }

        alertContainer.innerHTML = errorHtml;
    }
});