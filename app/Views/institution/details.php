<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>

<style>
    /* Uniform Dropdown Styling */
    .dropdown.is-right {
        position: relative;
    }

    .dropdown.is-right .dropdown-menu {
        right: 0;
        left: auto;
        min-width: 220px;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-radius: 8px;
        border: 1px solid #ddd;
        background: white;
    }

    /* Improve dropdown items */
    .dropdown-content .dropdown-item {
        padding: 12px 16px;
        font-size: 0.9rem;
        transition: background 0.2s ease-in-out;
    }

    .dropdown-content .dropdown-item:hover {
        background-color: #f5f5f5;
    }

    /* Button Styles */
    .dropdown-trigger .button,
    .download-button {
        display: flex;
        align-items: center;
        gap: 6px;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.9rem;
        background-color: #f5f5f5;
        transition: all 0.2s ease-in-out;
    }

    .dropdown-trigger .button:hover,
    .download-button:hover {
        background-color: #e8e8e8;
    }

    .institution-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border-bottom: 2px solid #ddd;
    }

    .media {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .media-left img {
        border-radius: 50%;
        width: 64px;
        height: 64px;
        object-fit: cover;
    }

    .file-container {
        text-align: center;
        padding: 30px;
        border: 2px dashed #ccc;
        border-radius: 10px;
        margin-top: 20px;
        background: #f9f9f9;

        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .file-container figure {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }

    .file-container img {
        width: 100px;
        height: auto;
    }

    .file-upload {
        margin-top: 5px;
        display: flex;
        justify-content: center;
    }
</style>

<body>
    <section class="section">
        <div class="container">
            <div class="box">
                <!-- Institution Info -->
                <div class="institution-info">
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img id="InstitutionImage" src="/images/institution.png" alt="Profile Picture">
                            </figure>
                        </div>
                    </div>
                    <div class="media-content">
                        <h1 class="title is-5 has-text-weight-bold">West Visayas State University</h1>
                    </div>

                    <!-- Spacer -->
                    <div class="column"></div>
                    <!-- Dropdown -->
                    <div class="field mr-4">
                        <div class="control">
                            <div class="select is-smaller" style="width: 200px; padding: 6px;">
                                <select id="categoryDropdown" onchange="navigateToCategory()">
                                    <option value="<?= base_url('institution/home') ?>">All</option>
                                    <option value="<?= base_url('institution/research_centers/index') ?>">Research,
                                        Development
                                        and Innovation Centers</option>
                                    <option value="<?= base_url('institution/consortium/index') ?>">Consortium
                                        Membership</option>
                                    <option value="<?= base_url('institution/projects/index') ?>">R&D Projects</option>
                                    <option value="<?= base_url('institution/balik_scientist/index') ?>">Balik
                                        Scientists</option>
                                    <option value="<?= base_url('institution/ncrp_members/index') ?>">NCRP Members
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Download Button -->
                    <div class="control">

                        <button class="button is-light is-small">
                            <span class="icon">
                                <i class="fas fa-download"></i>
                            </span>
                        </button>
                    </div>
                </div>



                <!-- File Upload Section -->
                <div class="box file-container">
                    <figure class="image is-128x128">
                        <img src="file-icon.png" id="uploadedFileIcon" alt="Institution Project">
                    </figure>
                    <p id="uploadedFileName" class="has-text-centered">No file uploaded</p>

                    <!-- File Upload Input -->
                    <div class="file-upload">
                        <input type="file" id="fileInput" class="button is-small is-link">
                    </div>
                </div>
    </section>
</body>

<script>
    function navigateToCategory() {
        let dropdown = document.getElementById('categoryDropdown');
        let selectedUrl = dropdown.value;
        if (selectedUrl) {
            window.location.href = selectedUrl;
        }
    }

    // Get URL Parameters
    const params = new URLSearchParams(window.location.search);
    const name = params.get('name');
    const image = params.get('image');
    const file = params.get('file');

    // Update Page Content
    document.getElementById("institutionName").value = name || "";
    document.getElementById("institutionImage").src = image || "/images/institution.png";
    document.getElementById("downloadFile").href = file || "#";

    // Handle File Upload
    document.getElementById("fileInput").addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            document.getElementById("uploadedFileName").textContent = file.name;
            document.getElementById("downloadFile").href = URL.createObjectURL(file);
            document.getElementById("downloadFile").download = file.name;
        }
    });
</script>

<?= $this->endSection() ?>