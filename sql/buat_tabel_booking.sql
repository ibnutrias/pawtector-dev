-- Tabel Appointments (Janji Temu)
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    pet_id INT NOT NULL,
    service ENUM('Boarding', 'Daycare', 'Grooming') DEFAULT 'Daycare',
    appointment_date DATE NOT NULL,
    appointment_time VARCHAR(10) NOT NULL, -- e.g., '09:00'
    notes TEXT,
    staff_notes TEXT, -- Catatan tambahan dari staff (optional, bisa juga via report)
    status ENUM('pending', 'active', 'finished', 'cancelled') DEFAULT 'pending',
    total_price DECIMAL(15, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
);

-- Tabel Appointment Reports (Laporan Harian/Aktivitas)
CREATE TABLE IF NOT EXISTS appointment_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    eating TINYINT(1) DEFAULT 0, -- 1 = Sudah makan/Bagus
    playing TINYINT(1) DEFAULT 0, -- 1 = Sudah bermain
    grooming TINYINT(1) DEFAULT 0, -- 1 = Selesai grooming
    staff_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);
