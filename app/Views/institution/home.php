<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>

<style>
    .card {
        position: relative;
    }

    .kebab-menu {
        position: absolute;
        bottom: 10px;
        right: 10px;
    }

    .image-upload {
        position: relative;
        cursor: pointer;
    }

    .image-upload input[type="file"] {
        display: none;
    }

    .image-upload-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f5f5;
        border: 2px dashed #ccc;
        height: 150px;
        position: relative;
    }

    .plus-icon {
        position: absolute;
        color: #aaa;
        font-size: 3rem;
        pointer-events: none;
        top: 50%;
        /* Vertically centered */
        left: 50%;
        /* Horizontally centered */
        transform: translate(-50%, -50%);
        /* Adjusts the element to be perfectly centered */
    }

    .modal-image-preview {
        display: block;
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 150px;
        object-fit: contain;
        margin-bottom: 5px;
        /* Reduce space below the image */
    }

    .modal-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px;
        /* Adjust padding */
    }

    #file-input {
        margin-top: 0;
        /* Ensure no extra space above the file input */
        padding-top: 0;
        /* Remove unnecessary padding */
    }
</style>

<body>

    <!-- Flex container to align elements properly -->
    <div class="field is-flex is-align-items-center is-justify-content-flex-end" style="width: 100%; gap: 10px;">

        <!-- Create New Button -->
        <div class="control">
            <a href="<?= base_url('institution/create') ?>" class="button is-primary"
                style="height: 36px; display: flex; align-items: center;">
                <span class="icon"><i class="fas fa-plus"></i></span>
                <span>Create New</span>
            </a>
        </div>

        <!-- Dropdown -->
        <div class="control">
            <div class="select is-smaller" style="width: 200px; height: 36px; display: flex; align-items: center;">
                <select id="categoryDropdown" onchange="navigateToCategory()" style="height: 100%;">
                    <option value="<?= base_url('institution/home') ?>">All</option>
                    <option value="<?= base_url('institution/research_centers/index') ?>">Research, Development and
                        Innovation Centers</option>
                    <option value="<?= base_url('institution/consortium/index') ?>">Consortium Membership</option>
                    <option value="<?= base_url('institution/projects/index') ?>">R&D Projects</option>
                    <option value="<?= base_url('institution/balik_scientist/index') ?>">Balik Scientists</option>
                    <option value="<?= base_url('institution/ncrp_members/index') ?>">NCRP Members</option>
                </select>
            </div>
        </div>

        <!-- Download Button -->
        <div class="control">
            <button class="button is-light is-small" style="height: 36px; display: flex; align-items: center;">
                <span class="icon">
                    <i class="fas fa-download"></i>
                </span>
            </button>
        </div>

    </div>
    <h1 class="title has-text-centered">Institutions</h1>

    <div class="columns is-multiline" id="card-container">

        <!-- Card Start -->
        <div class="column is-one-fifth-desktop is-half-tablet is-full-mobile">
            <div class="card card-link">

                <div class="card-image">
                    <label class="image-upload" onclick="event.stopPropagation();">
                        <input type="file" accept="image/*" onchange="previewImage(event, this)">
                        <figure class="image is-4by3 image-upload-placeholder">
                            <span class="plus-icon"><i class="fas fa-plus"></i></span>
                            <img src="https://via.placeholder.com/200x150?text=Upload+Image" alt="Institution Image"
                                class="preview-image" onclick="triggerFileInput(this)">

                        </figure>
                    </label>
                </div>

                <a href="<?= base_url('institution/details') ?>" class="card-content">
                    <p class="title is-5 card-title">West Visayas State University</p>
                    <p class="subtitle is-6 card-description">La Paz, Iloilo</p>
                </a>

                <!-- Kebab Menu -->
                <div class="dropdown is-right kebab-menu" onclick="event.stopPropagation();">
                    <div class="dropdown-trigger">
                        <button class="button is-white is-small" aria-haspopup="true">
                            <span class="icon is-small">
                                <i class="fas fa-ellipsis-v"></i>
                            </span>
                        </button>
                    </div>
                    <div class="dropdown-menu" role="menu">
                        <div class="dropdown-content">
                            <a href="#" class="dropdown-item" onclick="openEditModal(this); return false;"> ✏️ Edit</a>

                            <a href="#" class="dropdown-item has-text-danger" onclick="confirmDelete(this)">
                                🗑️ Delete
                            </a>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Card End -->

    </div>
    </div>
    </section>

    <!-- Modal for Editing -->
    <div class="modal" id="edit-modal">
        <div class="modal-background" onclick="closeEditModal()"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Edit Card</p>
                <button class="delete" aria-label="close" onclick="closeEditModal()"></button>
            </header>
            <section class="modal-card-body">
                <div class="field">
                    <label class="label">Change Image</label>
                    <div class="control">
                        <figure class="image is-4by3">
                            <img id="modal-image-preview"
                                src="https://via.placeholder.com/300x200.png?text=Upload+Image">
                        </figure>
                        <input type="file" accept="image/*" id="modal-image-input" onchange="previewModalImage(event)">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Title</label>
                    <div class="control">
                        <input class="input" type="text" id="edit-title">
                    </div>
                </div>
                <div class="field">
                    <label class="label">Description</label>
                    <div class="control">
                        <textarea class="textarea" id="edit-description"></textarea>
                    </div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button is-success" onclick="saveCardEdit()">Save</button>
                <button class="button" onclick="closeEditModal()">Cancel</button>
            </footer>
        </div>
    </div>

    <!-- JavaScript -->
    <script>

        function navigateToCategory() {
            let dropdown = document.getElementById('categoryDropdown');
            let selectedUrl = dropdown.value;
            if (selectedUrl) {
                window.location.href = selectedUrl;
            }
        }
        let currentEditingCard = null;

        function redirectToDetails(event, url) {
            window.location.href = url;
        }

        // Open modal and populate fields
        function openEditModal(element) {
            let card = element.closest('.card'); // Get the card container
            if (!card) return; // Exit if no card is found

            currentEditingCard = card;
            document.getElementById('edit-title').value = card.querySelector('.card-title').innerText;
            document.getElementById('edit-description').value = card.querySelector('.card-description').innerText;
            document.getElementById('modal-image-preview').src = card.querySelector('.preview-image').src;

            document.getElementById('edit-modal').classList.add('is-active');
        }

        // Close modal
        function closeEditModal() {
            document.getElementById('edit-modal').classList.remove('is-active');
        }

        // Preview image inside modal
        function previewModalImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('modal-image-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Apply changes to the card
        function saveCardEdit() {
            if (currentEditingCard) {
                currentEditingCard.querySelector('.card-title').innerText = document.getElementById('edit-title').value;
                currentEditingCard.querySelector('.card-description').innerText = document.getElementById('edit-description').value;
                currentEditingCard.querySelector('.preview-image').src = document.getElementById('modal-image-preview').src;
            }
            closeEditModal();
        }

        // Image preview on card itself
        function previewImage(event, input) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    input.closest('.card-image').querySelector('.preview-image').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Delete card
        function confirmDelete(index) {
        let modal = document.getElementById("confirmModal");
        modal.classList.add("custom-modal-active");

        document.getElementById("confirmText").innerText = "Are you sure you want to delete?";

        document.getElementById("confirmYes").onclick = function () {
            scientists.splice(index, 1);
            closeModal();
            renderCarousel();
        };
    }
        // Kebab menu toggle
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.dropdown-trigger button').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const dropdown = this.closest('.dropdown');
                    dropdown.classList.toggle('is-active');
                });
            });

            // Prevent the dropdown from closing when clicking inside
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown').forEach(d => d.classList.remove('is-active'));
            });
        });

    </script>

</body>



<?= $this->endSection() ?>