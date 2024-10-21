<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Bootstrap JS (with Popper.js for modals) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/style.css">
    <title>Contacts</title>
</head>
<body>
    <div class="container">
        <h1 style="margin-top: 10px">Your Contacts</h1>

        <?php
        session_start();
        include_once('../db/db.php');
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit();
        }

        // Show success or error messages
        if (isset($_SESSION['message'])) {
            $alertType = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info'; // Default to info if no type set
            echo '<div class="alert alert-' . htmlspecialchars($alertType) . ' alert-dismissible fade show" role="alert">' . htmlspecialchars($_SESSION['message']) . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="width: 10px">
                        <span aria-hidden="true">&times;</span>
                    </button>
                  </div>';
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
        }

        // Set number of contacts per page
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Fetch contacts with pagination
        $stmt = $conn->prepare('SELECT * FROM contacts WHERE user_id = ? ORDER BY name LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset);
        $stmt->execute([$_SESSION['user_id']]);
        $contacts = $stmt->fetchAll();

        // Get total number of contacts
        $total_contacts = $conn->prepare('SELECT COUNT(*) FROM contacts WHERE user_id = ?');
        $total_contacts->execute([$_SESSION['user_id']]);
        $total_contacts = $total_contacts->fetchColumn();

        $total_pages = ceil($total_contacts / $limit);
        ?>

        <a href="add_contact.php" class="btn btn-success mr-2">
            <i class="fas fa-plus"></i> New
        </a>

        <div style="display:inline-block; margin-right: 10px;">
            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#mergeModal">
                Merge Duplicate
            </button>
        </div>

        <div style="display:inline-block; margin-right: 10px;">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal">
                Import
            </button>
        </div>

        <div style="display:inline-block;">
            <form action="../api/export_vcf.php" method="GET" style="display:inline-block;">
                <button type="submit" class="btn btn-secondary">Export to VCF</button>
            </form>
        </div>

        <div style="display: inline-block; margin-top: 5px; margin-left: 310px;">
            <form action="../api/search_contacts.php" method="GET" style="display: flex; align-items: center;">
                <input type="text" name="query" class="form-control" placeholder="Search Contacts" style="width: 200px; margin-right: 10px; margin-top: 2px;">
                <button type="submit" class="btn btn-info" style="padding: 0; height:33px; width:33px;">
                    <i class="fas fa-search"></i> <!-- Search Icon -->
                </button>
            </form>
        </div>
       
        <!-- Logout Button -->
        <div style="display: inline-block; margin-top: 5px; margin-left: 10px;">
            <a href="../views/logout.php"  class="btn btn-teal" style="background-color: #008080; color: white;">Logout</a>
        </div>


        <!-- Import Contacts Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Contacts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width: 50px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="../api/import_vcf.php" method="POST" enctype="multipart/form-data" id="importForm">
                            <div class="form-group">
                                <label for="vcf_file">Choose VCF File:</label>
                                <input type="file" class="form-control-file" name="vcf_file" accept=".vcf" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" form="importForm">Upload File</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Merge Contacts Modal -->
        <div class="modal fade" id="mergeModal" tabindex="-1" role="dialog" aria-labelledby="mergeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mergeModalLabel">Merge Duplicate Contacts</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="width: 50px;">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="../api/merge_duplicates.php" method="POST" id="mergeForm">
                            <div class="form-group">
                                <label for="merge_option">Choose Merge Option:</label>
                                <select class="form-control" name="merge_option" id="merge_option" required>
                                    <option value="">Select an option</option>
                                    <option value="name">Merge by Name</option>
                                    <option value="number">Merge by Phone Number</option>
                                    <option value="email">Merge by Email</option> <!-- New option -->
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" form="mergeForm">Merge</button>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Tags</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $contact_number = $offset + 1; // Starting number for current page
                foreach ($contacts as $contact): ?>
                <tr>
                    <td><?= $contact_number++; ?></td> <!-- Display the contact number -->
                    <td><?= htmlspecialchars($contact['name']); ?></td>
                    <td><?= htmlspecialchars($contact['email']); ?></td>
                    <td><?= htmlspecialchars($contact['phone']); ?></td>
                    <td><?= htmlspecialchars($contact['tags']); ?></td>
                    <td>
                        <a href="add_contact.php?id=<?= $contact['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="../api/contact.php?delete=<?= $contact['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php if ($total_pages > 1): ?>
                    <!-- Show previous page link -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Show previous page -->
                    <?php if ($page - 1 > 0): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1; ?>"><?= $page - 1; ?></a>
                        </li>
                    <?php endif; ?>

                    <!-- Show current page -->
                    <li class="page-item active">
                        <span class="page-link"><?= $page; ?></span>
                    </li>

                    <!-- Show next page -->
                    <?php if ($page + 1 <= $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1; ?>"><?= $page + 1; ?></a>
                        </li>
                    <?php endif; ?>

                    <!-- Display last two pages if far from the last page -->
                    <?php if ($page < $total_pages - 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>

                    <?php if ($page < $total_pages - 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $total_pages - 1; ?>"><?= $total_pages - 1; ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="page-item <?= $page == $total_pages ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $total_pages; ?>"><?= $total_pages; ?></a>
                    </li>

                    <!-- Show next page link -->
                    <?php if ($page < $total_pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1; ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- If only one page, just show the current page (1) -->
                    <li class="page-item active">
                        <span class="page-link">1</span>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</body>
</html>
