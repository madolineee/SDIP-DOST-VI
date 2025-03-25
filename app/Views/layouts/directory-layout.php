<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>

<body>
    <div class="columns is-vcentered is-mobile px-4 py-3">
        <!-- Dropdown -->
        <div class="field mr-4">
            <label class="label has-text-weight-semibold">Select Category</label>
            <div class="control">
                <div class="select is-semi-medium is-fullwidth">
                    <select id="categoryDropdown" onchange="navigateToCategory()">
                        <option value="<?= base_url('directory/home') ?>">All</option>
                        <option value="<?= base_url('directory/regional_offices') ?>">Regional Offices</option>
                        <option value="<?= base_url('directory/nga') ?>">NGA</option>
                        <option value="<?= base_url('directory/academes') ?>">Academes</option>
                        <option value="<?= base_url('directory/lgus') ?>">LGUs</option>
                        <option value="<?= base_url('directory/business_sector') ?>">NGO Business Sector</option>
                        <option value="<?= base_url('directory/wide_contacts') ?>">DOST Wide-Contacts</option>
                    </select>
                </div>
            </div>
        </div>

            <!-- Buttons and Search -->
            <div class="column is-flex is-justify-content-flex-end is-align-items-center">
                <!-- <a href="<?= base_url('directory/regional_offices/create') ?>">
                    <button class="button is-primary is-outlined">
                        <span class="icon no-bg">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span>Create New</span>
                    </button>
                </a> -->

                <div class="control has-icons-left mx-2">
                    <input class="input" type="text" placeholder="Search">
                    <span class="icon is-small is-left no-bg">
                        <i class="fas fa-search"></i>
                    </span>
                </div>
                <button class="button is-outlined">Filter</button>
            </div>
        </div>

        <main>
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        function navigateToCategory() {
            var categoryUrl = document.getElementById("categoryDropdown").value;
            if (categoryUrl) {
                window.location.href = categoryUrl;
            }
        }
    </script>
</body>


</html>


<?= $this->endSection() ?>