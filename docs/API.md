# API Documentation

Base URL: `/api`

## Monitors

### List Monitors
`GET /monitors`

Returns a list of all monitors.

**Parameters:**
- `status` (optional): Filter by status (`up`, `down`, `warning`)
- `type` (optional): Filter by type (`website`, `api`, `ping`)
- `is_active` (optional): Filter by active status (`1`, `0`)

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Production API",
      "url": "https://api.example.com",
      "type": "api",
      "status": "up",
      "uptime_percentage": 99.9,
      "current_latency": 45,
      "last_checked_at": "2023-10-27T10:00:00.000000Z",
      "is_active": true,
      "tags": ["prod", "core"]
    }
  ]
}
```

### Create Monitor
`POST /monitors`

**Body:**
```json
{
  "name": "New Monitor",
  "url": "https://example.com",
  "type": "website",
  "interval": 5,
  "tags": ["staging"]
}
```

### Get Monitor
`GET /monitors/{id}`

Returns detailed information about a specific monitor.

### Update Monitor
`PUT /monitors/{id}`

**Body:**
Same as Create Monitor (all fields optional).

### Delete Monitor
`DELETE /monitors/{id}`

Soft deletes a monitor.

---

## Health Checks

### Get History
`GET /monitors/{id}/health-checks`

**Parameters:**
- `days` (optional): Number of days to retrieve (default: 7)

### Get Timeline
`GET /monitors/{id}/timeline`

Returns data formatted for uptime charts.

### Recent Events
`GET /health-checks/recent`

Returns the most recent health check events across all monitors.

---

## Statistics

### Dashboard Stats
`GET /monitors/stats`

Returns aggregated statistics for the dashboard.

**Response:**
```json
{
  "uptime_percentage": 99.95,
  "avg_latency": 120,
  "up_monitors": 10,
  "down_monitors": 0,
  "warning_monitors": 1
}
```

---

## Network Intelligence

### Get Client Info
`GET /network/info`

Returns public IP and network information for the requester.
