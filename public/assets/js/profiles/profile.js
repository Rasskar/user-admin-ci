document.addEventListener("DOMContentLoaded", function () {
    let cropper;
    const imageInput = document.getElementById("photoLink");
    const photoPreview = document.getElementById("photoPreview");
    const cropModal = new bootstrap.Modal(document.getElementById("cropperModal"));
    const cropSave = document.getElementById("cropSave");

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

    /*document.getElementById("profileForm").addEventListener('submit', async function(event) {
        event.preventDefault();

        const form = event.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const formData = new FormData(form);

        console.log(submitButton);
        console.log(form);
        console.log(formData);

        submitButton.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Сохранение...`;
        submitButton.disabled = true;

        try {
            const response = await fetch('/profile/update', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const result = await response.json();

            document.getElementById('csrf_token').value = result.csrf_token;
            //document.querySelector('input[name="<?= csrf_token() ?>"]').value = result.csrf_token;


            console.log(result);
            console.log(response);


            /!*!// Обновляем CSRF-токен


            const alertBox = document.getElementById("alertBox");
            alertBox.classList.remove("d-none");

            if (response.ok) {
                alertBox.classList.remove("alert-danger");
                alertBox.classList.add("alert-success");
                alertBox.textContent = result.message;

                // Очищаем ошибки
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

                // Скрываем сообщение через 15 сек.
                setTimeout(() => alertBox.classList.add("d-none"), 15000);
            } else {
                alertBox.classList.remove("alert-success");
                alertBox.classList.add("alert-danger");
                alertBox.textContent = "Ошибка при сохранении данных.";

                if (result.errors) {
                    Object.keys(result.errors).forEach(field => {
                        const input = document.getElementById(field);
                        if (input) {
                            input.classList.add("is-invalid");
                            document.getElementById(field + "Error").textContent = result.errors[field];
                        }
                    });
                }
            }*!/
        } catch (error) {
            console.error("Ошибка запроса:", error);
            /!*alertBox.classList.remove("alert-success");
            alertBox.classList.add("alert-danger");
            alertBox.textContent = "Ошибка соединения с сервером.";*!/
        } finally {
            submitButton.innerHTML = "Сохранить";
            submitButton.disabled = false;
        }
    });*/
});