<?= $this->extend('layouts/header-layout') ?>
<?= $this->section('content') ?>


<style>
    body {
        background-color: #fff;
    }


    .section {
        padding: 40px;
    }

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
        margin-top: 36px;
        /* Adjust the ellipsis alignment */
    }
</style>


<body>

    <div class="container">
        <div class="box mt-4">

            <div class="title">
                <h1 class="title has-text-centered">Research Projects</h1>
            </div>

            <!-- Buttons beside tabs -->
            <div class="buttons-container">
                <a href="<?= base_url('institution/projects/create') ?>" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Create New</span>
                </a>
                <button class="button is-light">
                    <span class="icon"><i class="fas fa-download"></i></span>
                    <span>Download Template</span>
                </button>
            </div>

            <div>
                <p>Completed / Pending / Ongoing</p>

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
        </div>
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