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

    h2 {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 10px;
    }

    .carousel {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 90%;
        max-width: 900px;
        overflow: hidden;
        position: relative;
        margin: 0 auto;
    }

    .carousel-container {
        display: flex;
        scroll-behavior: smooth;
        width: 100%;
        padding: 60px;
        overflow-x: auto;
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-snap-type: x mandatory;
    }

    .carousel-item {
        min-width: 180px;
        max-width: 200px;
        margin: 5px;
        transition: transform 0.4s ease-in-out;
        position: relative;
        background: white;
        padding: 8px;
        border-radius: 8px;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        scroll-snap-align: start;
    }

    .prev,
    .next {
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        font-size: 20px;
        cursor: pointer;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 10;
        padding: 8px;
        border-radius: 8px;
    }

    .prev:hover,
    .next:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .prev {
        left: 10px;
    }

    .next {
        right: 10px;
    }

    .dropdown {
        position: absolute;
        bottom: 5px;
        right: 5px;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        background: white;
        box-shadow: 0px 1px 6px rgba(0, 0, 0, 0.1);
        padding: 3px;
        border-radius: 3px;
        right: 0;
        z-index: 10;
        width: 70px;
    }

    .dropdown-item {
        font-size: 11px;
        padding: 2px 4px;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .dropdown-divider {
        margin: 2px 0;
    }

    .dropdown.active .dropdown-menu {
        display: block;
    }

    /* Modal Styles */
    .custom-modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 250px;
        background: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        z-index: 100;
        text-align: center;
        padding: 15px;
    }

    .custom-modal-active {
        display: block;
    }

    .custom-modal-content {
        padding: 10px;
    }

    .custom-modal-text {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 12px;
    }

    .custom-modal-footer {
        display: flex;
        justify-content: space-between;
        gap: 8px;
        margin-top: 10px;
    }

    .custom-modal-btn {
        flex: 1;
        padding: 6px 12px;
        font-size: 13px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .confirm-btn {
        background-color: #4CAF50;
        color: white;
    }

    .cancel-btn {
        background-color: #f44336;
        color: white;
    }
</style>



<body>
    <div class="container">
        <div class="box mt-4">

            <div class="title">
                <h1 class="title has-text-centered">DOST VI NRCP Members</h1>
            </div>

            <!-- Buttons beside tabs -->
            <div class="buttons-container">
                <a href="<?= base_url('institution/nrcp_members/create') ?>" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Create New</span>
                </a>

                <button class="button is-light">
                    <span class="icon"><i class="fas fa-download"></i></span>
                    <span>Download Template</span>
                </button>
            </div>

            <div class="carousel">
                <button class="prev" onclick="scrollCarousel(-1)">&#10094;</button>
                <div class="carousel-container" id="carouselContainer">
                    <?php if (!empty($nrcp_members)): ?>
                        <?php foreach ($nrcp_members as $nrcp): ?>
                            <div class="carousel-item">
                                <img src="<?= !empty($nrcp['image']) ? base_url($nrcp['image']) : '/images/profile.png' ?>"
                                    alt="<?= esc($nrcp['first_name']) ?>" width="100%">


                                <p class="card-title">
                                    <a href="<?= base_url('institution/nrcp_members/view/' . $nrcp['id']) ?>"
                                        class="nrcp-link">
                                        <?= esc($nrcp['honorifics']) . ' ' . esc($nrcp['first_name']) . ' ' . esc($nrcp['middle_name']) . ' ' . esc($nrcp['last_name']) ?>
                                    </a>
                                </p>

                                <!-- <p style="font-size: 12px; margin-top: 4px;"> -->
                                <!-- <?= esc($nrcp['description']) ?> -->
                                <!-- </p> -->

                                <small><?= esc($nrcp['institution_name']) ?></small>

                                <!-- Dropdown Menu -->
                                <div class="dropdown">
                                    <button onclick="toggleDropdown(event, this)">⋮</button>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-content">
                                            <a href="<?= base_url('institution/nrcp_members/edit/' . esc($nrcp['id'])) ?>"
                                                class="dropdown-item has-text-link">
                                                ✏️ Edit
                                            </a>
                                            <hr class="dropdown-divider">
                                            <a href="#" class="dropdown-item has-text-danger"
                                                onclick="confirmDelete(<?= esc($nrcp['id']) ?>)">
                                                🗑️ Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No NCRP Members found.</p>
                    <?php endif; ?>
                </div>
                <button class="next" onclick="scrollCarousel(1)">&#10095;</button>
            </div>
        </div>
    </div>

    <script>
        function scrollCarousel(direction) {
            const container = document.getElementById("carouselContainer");
            const scrollAmount = 280;
            container.scrollBy({ left: direction * scrollAmount, behavior: "smooth" });
        }

        function toggleDropdown(event, button) {
            event.stopPropagation();
            closeAllDropdowns();
            button.parentElement.classList.toggle("active");
        }

        function closeAllDropdowns() {
            document.querySelectorAll(".dropdown").forEach(d => d.classList.remove("active"));
        }

        document.addEventListener("click", closeAllDropdowns);

        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this scientist?")) {
                window.location.href = "<?= base_url('institution/nrcp_members/delete/') ?>" + id;
            }
        }
    </script>
</body>
<?= $this->endSection() ?>