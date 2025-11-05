# TODO: Remove Welcome and Documentation from Admin Dashboard

- [x] Edit AdminPanelProvider.php to disable AccountWidget (welcome message) and ensure only OverviewStatsWidget is shown on dashboard (removing FilamentInfoWidget with documentation link)

# TODO: Integrate Midtrans Configuration with Store Model

- [x] Create migration to add midtrans_client_key, midtrans_server_key, is_production columns to stores table
- [x] Modify MidtransService to fetch Midtrans configuration from Store model instead of config
- [x] Run migration to update database schema
