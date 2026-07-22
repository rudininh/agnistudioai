# Content API

## Content Items
/api/v1/workspaces/{workspace}/content-items mendukung list, create, read, update, dan archive. Filter utama: status, channel, scheduled date; sort default -updated_at. Transisi status dijalankan endpoint action agar aturan pipeline dapat divalidasi dan diaudit.

## Content Ideas
/api/v1/workspaces/{workspace}/content-ideas untuk mengelola ide konten yang masuk ke konten.

**Endpoints:**
- GET /content-ideas - Daftar ide dengan filter (status, priority, opportunity_score_min, tags)
- POST /content-ideas - Buat ide baru
- GET /content-ideas/{id} - Detail ide termasuk opportunity score
- PATCH /content-ideas/{id} - Update ide
- DELETE /content-ideas/{id} - Hapus ide
- POST /content-ideas/{id}/submit-for-review - Ajukan untuk review
- POST /content-ideas/{id}/approve - Setujui ide
- POST /content-ideas/{id}/reject - Tolak ide
- POST /content-ideas/{id}/schedule - Jadwalkan untuk produksi

**Query Parameters:**
- status: new, reviewed, in_progress, approved, rejected
- priority_min: 1-5
- opportunity_score_min: 0-100
- 	ags: array of tags
- sort: priority, opportunity_score, created_at, updated_at

## Opportunity Scores
/api/v1/workspaces/{workspace}/opportunity-scores untuk mengakses skor peluang yang dihitung oleh Content Intelligence Engine.

**Endpoints:**
- GET /opportunity-scores - Daftar skor dengan filter (idea_id, min_score, confidence_level)
- GET /opportunity-scores/{id} - Detail skor termasuk breakdown komponen
- POST /opportunity-scores/{id}/refresh - Paksa perhitungan ulang skor

## AI Interactions
/api/v1/workspaces/{workspace}/ai-interactions untuk melacak penggunaan AI dan feedback.

**Endpoints:**
- GET /ai-interactions - Filters by interaction_type, date_range, user_feedback
- POST /ai-interactions - Catat interaksi baru (prompt, response, model used)
- PATCH /ai-interactions/{id}/feedback - Beri feedback (helpful/neutral/unhelpful)

## Recommendations
/api/v1/workspaces/{workspace}/recommendations untuk rekomendasi yang diberikan oleh sistem.

**Endpoints:**
- GET /recommendations - Filters by type, entity_id, was_accepted, date_range
- GET /recommendations/{id} - Detail rekomendasi beserta hasilnya jika diterapkan
- PATCH /recommendations/{id}/accept - Tandai sebagai diterima
- PATCH /recommendations/{id}/reject - Tandai sebagai ditolak
- POST /recommendations/{id}/outcome - Catat hasil aktual jika telah dilaksanakan

## Trends
/api/v1/workspaces/{workspace}/trends untuk mengakses data tren dari berbagai platform.

**Endpoints:**
- GET /trends - Filters by platform, keyword, date_range, min_score
- GET /trends/top - Tren teratas untuk periode waktu tertentu
- GET /trends/rising - Tren yang naik tajam (velocity > threshold)

## Analytics
/api/v1/workspaces/{workspace}/analytics untuk data performa konten.

**Endpoints:**
- GET /analytics - Filters by content_item_id, platform, event_type, date_range
- GET /analytics/summary - Ringkasan metrik kinerja (views, engagement rate, etc.)
- GET /analytics/trends - Tren metrik seiring waktu
