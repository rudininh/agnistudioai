# Events

Initial events: `ContentIdeaCaptured`, `ContentStatusChanged`, `AiExecutionCompleted`, `PublicationRecorded`, `MetricsImported`, and `RecommendationGenerated`.

Events bersifat immutable, berisi workspace ID dan correlation ID, serta diproses async bila tidak diperlukan untuk respons pengguna. Gunakan outbox pattern sebelum event lintas proses menjadi kritikal.
