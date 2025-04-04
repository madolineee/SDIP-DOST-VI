<?= $this->extend('layouts/header-layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <h1>Project Details</h1>

    <!-- Display error message if project not found -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if ($project): ?>
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Field</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Research Name</strong></td>
                            <td><?= esc($project['research_name']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td><?= esc($project['status']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Description</strong></td>
                            <td><?= esc($project['description']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Sector</strong></td>
                            <td><?= esc($project['sector']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Duration</strong></td>
                            <td><?= esc($project['duration']) ?> months</td>
                        </tr>
                        <tr>
                            <td><strong>Project Leader</strong></td>
                            <td><?= esc($project['project_leader']) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Objectives</strong></td>
                            <td>
                                <?php
                                // Automatically break lines on periods
                                $objectives = esc($project['project_objectives']);
                                $objectives = nl2br(implode('.<br>', explode('.', $objectives)));
                                echo $objectives;
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Approved Amount</strong></td>
                            <td>$<?= number_format($project['approved_amount'], 2) ?></td>
                        </tr>
                        <tr>
                            <td><strong>Stakeholder Name</strong></td>
                            <td><?= esc($project['stakeholder_name']) ?></td>
                        </tr>
                    </tbody>
                </table>

                <hr>

                <a href="<?= site_url('/institution/projects/index') ?>" class="btn btn-secondary">Back to Projects</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
