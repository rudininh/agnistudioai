# Clean Architecture

Controller menerima HTTP; Form Request memvalidasi; Action/Service menjalankan use case; model/repository mengakses persistence; Resource mengembalikan transport response. Domain tidak bergantung pada provider AI atau SDK sosial. Gunakan interface hanya ketika terdapat implementasi alternatif atau boundary eksternal yang nyata.
