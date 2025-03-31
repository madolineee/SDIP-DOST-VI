<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>


<style>
    body {
        background-color: #fff;
    }


    .section {
        padding: 40px;
    }


    .custom-box {
        background: white;
        padding: 0px;
        border-radius: 8px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    }


    .status-badge {
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: bold;
    }


    .completed {
        background-color: #28a745;
        color: white;
    }


    .pending {
        background-color: #ff8c00;
        color: white;
    }


    .ongoing {
        background-color: #ffc107;
        color: white;
    }

    .dropdown-trigger {
        margin-top: 36px; /* Adjust the ellipsis alignment */
    }
</style>


<body>
    <div class="field is-flex is-align-items-center is-justify-content-flex-end" style="width: 100%; gap: 10px;">
        <div class="control">
            <a href="<?= base_url('institution/projects/create') ?>" class="button is-primary">
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

    <div class="box mt-4">
        <h2 class="title is-4">Research Projects</h2>
        <p>Completed / Pending / Ongoing</p>
        <br>

        <?php if (!empty($research_projects)): ?>
            <?php foreach ($research_projects as $project): ?>
                <div class="box is-flex is-justify-content-space-between is-align-items-center" style="cursor: pointer;"
                    onclick="window.location.href='<?= base_url('institution/projects/view/' . $project['id']) ?>'">
                    <div>

                        <strong><?= esc($project['name']) ?></strong>
                        <p><?= esc($project['description']) ?></p>


                        <?php
                        $statusClass = '';
                        $statusIcon = '';

                        if (strtolower(trim($project['status'])) == 'completed') {
                            $statusClass = 'completed';
                            $statusIcon = '<i class="fas fa-check-circle"></i>';

                        } elseif (strtolower(trim($project['status'])) == 'pending') {
                            $statusClass = 'pending';
                            $statusIcon = '<i class="fas fa-clock"></i>';

                        } elseif (strtolower(trim($project['status'])) == 'ongoing') {
                            $statusClass = 'ongoing';
                            $statusIcon = '<i class="fas fa-spinner"></i>';
                        }

                        ?>

                        <span class="status-badge <?= $statusClass ?>">
                            <?= $statusIcon ?>         <?= strtoupper($project['status']) ?>
                        </span>
                    </div>
                    <div class="is-flex is-align-items-center" onclick="event.stopPropagation();">
                        <div class="dropdown is-hoverable is-right">
                            <div class="dropdown-trigger">
                                <button class="button is-white is-small">
                                    <span class="icon is-small">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </span>
                                </button>
                            </div>
                            <div class="dropdown-menu">
                                <div class="dropdown-content">
                                    <a href="<?= base_url('institution/projects/edit/' . $project['id']) ?>"
                                        class="dropdown-item edit-button">
                                        Edit
                                    </a>
                                    <a href="<?= base_url('institution/projects/delete/' . $project['id']) ?>"
                                        class="dropdown-item has-text-danger">
                                        Delete
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No research projects found.</p>
        <?php endif; ?>
    </div>

    <script>
        function navigateToCategory() {
            let dropdown = document.getElementById('categoryDropdown');
            let selectedUrl = dropdown.value;
            if (selectedUrl) {
                window.location.href = selectedUrl;
            }
        }
    </script>
</body>

<?= $this->endSection() ?>