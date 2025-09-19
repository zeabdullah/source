The `AuditScreen` model serves as a junction/pivot table to manage the many-to-many relationship between `Audit` and `Screen` models. This is necessary because:

-   One `Audit` can analyze multiple `Screens` (typically 2-7 for a flow audit).
-   One `Screen` can be included in multiple `Audits` (appearing in different user flows).

The `AuditScreen` model will store the following attributes:

-   `audit_id`: The ID of the audit.
-   `screen_id`: The ID of the screen being audited.
-   `sequence_order`: The order of the screen within the audit flow (e.g., 1st, 2nd, 3rd).
-   `created_at`/`updated_at`: Standard timestamps.

This approach aligns with standard Laravel practices for many-to-many relationships, especially when additional information (like `sequence_order`) needs to be stored about the relationship itself.

Should I proceed with creating this junction model, or is there an alternative approach you'd prefer for managing the audit-screen relationship?
