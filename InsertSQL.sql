--- Du lieu mau cho ban insert

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

INSERT INTO movies 
  (title, genre, duration, release_date, summary, status, is_now_showing)
VALUES
-- ======== PHIM ĐANG CHIẾU ========
('Cục vàng của ngoại','Gia đình, Tâm lý',119,'2025-10-17','Câu chuyện ấm áp về tình bà cháu trong một xóm nhỏ chan chứa nghĩa tình.','published',1),
('Gió vẫn thổi','Hoạt hình',127,'2025-10-17','Câu chuyện về kỹ sư hàng không Jirō giằng xé giữa đam mê bay lượn và hiện thực chiến tranh.','published',1),
('Tử chiến trên không','Hành động, Hồi hộp',118,'2025-09-19','Phim hành động lấy cảm hứng từ vụ cướp máy bay có thật tại Việt Nam sau 1975.','published',1),
('Nhà ma xó','Gia đình, Kinh dị',108,'2025-10-24','Bà Hiền và ba người con gặp hàng loạt hiện tượng kỳ quái khi vớt được chiếc khạp bí ẩn từ dòng sông.','published',1),
('Tay anh giữ một vì sao','Hài, Tình cảm',117,'2025-10-03','Siêu sao Hàn mắc kẹt tại Việt Nam, gặp cô gái bán cà phê đầy đam mê.','published',1),
('Cậu bé cá heo và bí mật 7 đại dương','Hoạt hình',96,'2025-10-03','Cậu Bé Cá Heo khám phá nguồn gốc bản thân và bảo vệ đại dương yên bình.','published',1),

-- ======== PHIM SẮP CHIẾU ========
('Bà đừng buồn con','Gia đình, Tâm lý',150,'2025-12-12','Tiến – cậu thiếu niên sống cùng bà nội, tìm thấy nghị lực và khát vọng giữa nghèo khó.','published',0),
('Phòng trọ ma bầu','Hài, Kinh dị',160,'2025-12-07','Hai người bạn thuê phải căn trọ kỳ bí và gặp hồn ma “ma bầu” đầy xúc động.','published',0),
('Phi vụ động trời 2','Gia đình, Hành động, Phiêu lưu, Thần thoại',140,'2025-12-20','Nick và Judy trở lại trong hành trình phiêu lưu Zootopia 2.','published',0),
('Ai thương ai mến','Gia đình, Hài, Tâm lý',125,'2026-01-01','Hành trình người phụ nữ miền Tây tìm lại yêu thương và bình yên.','published',0),
('Quán Kỳ Nam','Tâm lý, Tình cảm',120,'2025-12-28','Mối nhân duyên giữa Khang – chàng dịch giả và góa phụ Kỳ Nam.','published',0),
('Spongebob: Lời nguyền hải tặc','Hài, Hoạt hình, Phiêu lưu',135,'2025-12-16','SpongeBob đối đầu hồn ma Người Hà Lan bay giữa đại dương sâu thẳm.','published',0);

SET FOREIGN_KEY_CHECKS = 1;
