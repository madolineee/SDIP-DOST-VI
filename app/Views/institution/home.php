<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>

<style>
    .buttons-container {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-bottom: 10px;
        margin-top: -10px;
    }

    .title {
        font-size: 2.2rem;
        margin-top: 10px;
        margin-bottom: 1px;
    }

    .box {
        margin-top: 30px;
    }


    .card {
        border-radius: 8px;
        margin-top: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .card-image {
        width: 100%;
        height: auto;
        aspect-ratio: 1/1;
        /* Maintains a square shape */
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card-image img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        /* Ensures the whole image is visible */
    }


    .card-title {
        font-size: 1.1rem;
        font-weight: bold;
    }

    .card-description {
        font-size: 0.95rem;
        color: #4a4a4a;
    }

     /* Kebab Menu Fix */
     .kebab-menu {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .button.is-white {
        background: none;
        border: none;
        box-shadow: none;
    }

    .button.is-white:hover {
        background: rgba(0, 0, 0, 0.05);
    }

</style>

<body>
    <div class="container">
        <div class="box mt-4">

            <div class="title">
                <h1 class="title has-text-centered">Institutions</h1>
            </div>

            <!-- Buttons beside tabs -->
            <div class="buttons-container">
                <a href="<?= base_url('institution/create') ?>" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Create New</span>
                </a>
                <button class="button is-light">
                    <span class="icon"><i class="fas fa-download"></i></span>
                    <span>Download Template</span>
                </button>
            </div>


            <!-- Tab Content Below -->
            <div class="columns is-multiline" id="card-container">
                <?php foreach ($institutions as $institution): ?>
                    <div class="column is-one-fifth-desktop is-half-tablet is-full-mobile">
                        <div class="card">
                            <div class="card-image">
                                <img src="<?= !empty($institution['image']) ? base_url($institution['image']) : 'https://via.placeholder.com/200x150?text=No+Image' ?>"
                                    alt="Institution Image" class="preview-image">
                            </div>
                            <div class="dropdown is-right kebab-menu">
                                <div class="dropdown-trigger">
                                    <button class="button is-white is-small">
                                        <span class="icon is-small">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </span>
                                    </button>
                                </div>
                                <div class="dropdown-menu" role="menu">
                                    <div class="dropdown-content">
                                        <a href="<?= base_url('institution/edit/' . $institution['id']) ?>"
                                            class="dropdown-item">✏️
                                            Edit</a>
                                        <a href="<?= base_url('institution/delete/' . $institution['id']) ?>"
                                            class="dropdown-item has-text-danger" onclick="confirmDelete(this)">🗑️
                                            Delete</a>
                                    </div>
                                </div>
                            </div>


                            <!-- Institution Details -->
                            <div class="card-content">
                                <p class="card-title">
                                    <a href="<?= base_url('institution/view/' . $institution['id']) ?>"
                                        class="institution-link">
                                        <?= esc($institution['name']) ?> (<?= esc($institution['abbreviation']) ?>)
                                    </a>
                                </p>
                                <p class="card-description">
                                    <?= esc($institution['street']) ?>, <?= esc($institution['barangay']) ?>,
                                    <?= esc($institution['municipality']) ?>, <?= esc($institution['province']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>

<script>
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

<?= $this->endSection() ?>