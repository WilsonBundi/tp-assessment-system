-- Insert roles and permissions into auth_item
INSERT INTO auth_item (name, type, description, created_at, updated_at) VALUES
('tp_supervisor', 1, 'Teaching Practice Supervisor', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_coordinator', 1, 'Zone Coordinator', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_office', 1, 'TP Office', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_department_chair', 1, 'Department Chair', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_view_dashboard', 2, 'View TP Dashboard', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_create_assessment', 2, 'Create new assessment', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_edit_assessment', 2, 'Edit assessment', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_submit_assessment', 2, 'Submit assessment', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_view_assessment', 2, 'View assessment', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_review_assessment', 2, 'Review assessment', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_validate_assessment', 2, 'Validate assessment', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_reject_assessment', 2, 'Reject assessment', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_view_report', 2, 'View assessment report', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_download_report', 2, 'Download assessment report', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_manage_students', 2, 'Manage student records', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_manage_lecturers', 2, 'Manage lecturer records', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_view_audit_log', 2, 'View audit logs', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
('tp_view_analytics', 2, 'View analytics', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

-- Assign permissions to roles (auth_item_child: parent=role, child=permission)
INSERT INTO auth_item_child (parent, child) VALUES
-- Supervisor permissions
('tp_supervisor', 'tp_view_dashboard'),
('tp_supervisor', 'tp_create_assessment'),
('tp_supervisor', 'tp_edit_assessment'),
('tp_supervisor', 'tp_submit_assessment'),
('tp_supervisor', 'tp_view_assessment'),
('tp_supervisor', 'tp_view_report'),
('tp_supervisor', 'tp_download_report'),
-- Coordinator permissions (inherits supervisor + review/validate)
('tp_coordinator', 'tp_supervisor'),
('tp_coordinator', 'tp_review_assessment'),
('tp_coordinator', 'tp_validate_assessment'),
('tp_coordinator', 'tp_reject_assessment'),
('tp_coordinator', 'tp_view_audit_log'),
('tp_coordinator', 'tp_view_analytics'),
-- TP Office permissions
('tp_office', 'tp_view_dashboard'),
('tp_office', 'tp_view_assessment'),
('tp_office', 'tp_view_report'),
('tp_office', 'tp_download_report'),
('tp_office', 'tp_manage_students'),
('tp_office', 'tp_manage_lecturers'),
('tp_office', 'tp_view_audit_log'),
('tp_office', 'tp_view_analytics'),
-- Department Chair permissions (inherits coordinator + manage lecturers)
('tp_department_chair', 'tp_coordinator'),
('tp_department_chair', 'tp_manage_lecturers');

-- Assign tp_office role to admin user (user_id=1)
INSERT INTO auth_assignment (item_name, user_id, created_at) VALUES
('tp_office', 1, UNIX_TIMESTAMP());
