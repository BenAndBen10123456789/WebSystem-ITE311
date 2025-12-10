<?= $this->extend('template') ?>

<?= $this->section('title') ?><?= esc($title) ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2">Manage Students</h1>
            <p class="text-muted">Course: CS101 – Computer Science Basics</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Search Bar -->
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="searchInput" placeholder="Search by Student Name, ID, or Email">
                        </div>
                        <!-- Filters -->
                        <div class="col-md-2">
                            <select class="form-select" id="yearLevelFilter">
                                <option value="">All Year Levels</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="dropped">Dropped</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="programFilter">
                                <option value="">All Programs</option>
                                <option value="CS">Computer Science</option>
                                <option value="IT">Information Technology</option>
                                <option value="IS">Information Systems</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student List Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Student List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= esc($student['id']) ?></td>
                                    <td><?= esc($student['name']) ?></td>
                                    <td><?= esc($student['email']) ?></td>
                                    <td><?= esc($student['program'] ?? 'N/A') ?></td>
                                    <td><?= esc($student['year_level'] ? $student['year_level'] . ' Year' : 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $student['status'] === 'active' ? 'success' : ($student['status'] === 'inactive' ? 'warning' : 'danger') ?>">
                                            <?= ucfirst($student['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary me-2" onclick="viewDetails(<?= $student['id'] ?>)">
                                            <i class="bi bi-eye"></i> View Details
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning me-2" onclick="updateStatus(<?= $student['id'] ?>, '<?= esc($student['status']) ?>')">
                                            <i class="bi bi-pencil"></i> Update Status
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="removeStudent(<?= $student['id'] ?>)">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Student Details Modal -->
<div class="modal fade" id="studentDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Student Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Student ID:</strong> <span id="detailStudentId"></span></p>
                        <p><strong>Full Name:</strong> <span id="detailFullName"></span></p>
                        <p><strong>Email:</strong> <span id="detailEmail"></span></p>
                        <p><strong>Program / Major:</strong> <span id="detailProgram"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Year Level:</strong> <span id="detailYearLevel"></span></p>
                        <p><strong>Section:</strong> <span id="detailSection"></span></p>
                        <p><strong>Enrollment Date:</strong> <span id="detailEnrollmentDate"></span></p>
                        <p><strong>Status:</strong> <span id="detailStatus"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusUpdateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Student Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="statusUpdateForm">
                    <input type="hidden" id="statusStudentId" name="student_id">
                    <div class="mb-3">
                        <label for="currentStatus" class="form-label">Current Status</label>
                        <input type="text" class="form-control" id="currentStatus" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select class="form-select" id="newStatus" name="new_status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="dropped">Dropped</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3" placeholder="Optional remarks for the status change"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveStatusUpdate()">Save</button>
            </div>
        </div>
    </div>
</div>

<script>
// Search and Filter functionality
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('yearLevelFilter').addEventListener('change', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('programFilter').addEventListener('change', filterTable);

function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const yearLevel = document.getElementById('yearLevelFilter').value;
    const status = document.getElementById('statusFilter').value;
    const program = document.getElementById('programFilter').value;
    const rows = document.querySelectorAll('#studentsTable tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        const studentId = cells[0].textContent.toLowerCase();
        const name = cells[1].textContent.toLowerCase();
        const email = cells[2].textContent.toLowerCase();
        const rowProgram = cells[3].textContent.toLowerCase();
        const rowYearLevel = cells[4].textContent.toLowerCase();
        const rowStatus = cells[5].textContent.toLowerCase();

        const matchesSearch = studentId.includes(searchTerm) || name.includes(searchTerm) || email.includes(searchTerm);
        const matchesYearLevel = !yearLevel || rowYearLevel.includes(yearLevel);
        const matchesStatus = !status || rowStatus.includes(status);
        const matchesProgram = !program || rowProgram.includes(program);

        row.style.display = matchesSearch && matchesYearLevel && matchesStatus && matchesProgram ? '' : 'none';
    });
}

// View Details Modal
function viewDetails(studentId) {
    // Mock data - in real implementation, fetch from server
    const mockData = {
        id: studentId,
        name: 'John Doe',
        email: 'john.doe@example.com',
        program: 'Computer Science',
        yearLevel: '3rd Year',
        section: 'A',
        enrollmentDate: '2023-08-15',
        status: 'Active'
    };

    document.getElementById('detailStudentId').textContent = mockData.id;
    document.getElementById('detailFullName').textContent = mockData.name;
    document.getElementById('detailEmail').textContent = mockData.email;
    document.getElementById('detailProgram').textContent = mockData.program;
    document.getElementById('detailYearLevel').textContent = mockData.yearLevel;
    document.getElementById('detailSection').textContent = mockData.section;
    document.getElementById('detailEnrollmentDate').textContent = mockData.enrollmentDate;
    document.getElementById('detailStatus').textContent = mockData.status;

    new bootstrap.Modal(document.getElementById('studentDetailsModal')).show();
}

// Update Status Modal
function updateStatus(studentId, currentStatus) {
    document.getElementById('statusStudentId').value = studentId;
    document.getElementById('currentStatus').value = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
    document.getElementById('newStatus').value = currentStatus;
    document.getElementById('remarks').value = '';

    new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
}

// Save Status Update
function saveStatusUpdate() {
    const form = document.getElementById('statusUpdateForm');
    const formData = new FormData(form);

    // In real implementation, send to server
    console.log('Updating status for student:', formData.get('student_id'), 'to', formData.get('new_status'));

    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('statusUpdateModal')).hide();

    // Show success message (mock)
    alert('Status updated successfully!');
}

// Remove Student
function removeStudent(studentId) {
    if (confirm('Are you sure you want to remove this student from the course?')) {
        // In real implementation, send to server
        console.log('Removing student:', studentId);
        alert('Student removed successfully!');
    }
}
</script>

<?= $this->endSection() ?>
