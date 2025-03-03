-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 03:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `snapverse_studios`
--

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `name`, `role`, `image`) VALUES
(1, 'Rushi Shinde', 'Wedding Photographer', 'uploads/rushishinde.jpg'),
(2, 'Tejas Shelke', 'Maternity & Baby Portraits Photographer', 'uploads/tejas.jpg'),
(3, 'Trilochan Bhawedi', 'Fashion and Portraits Photographer', 'uploads/trilochan.jpg'),
(4, 'Animesh Kshatriya', 'Cinematographer & Video Editor', 'uploads/animesh.jpg'),
(5, 'Mandodar Pardhi', 'Photographer & Cinematographer', 'uploads/mandodar.jpg'),
(6, 'Pavan Valvi ', 'Traditional Photographer', 'uploads/pavan1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `booking_status` varchar(50) DEFAULT 'Pending',
  `package_id` int(11) DEFAULT NULL,
  `event_location` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `c_id` int(10) NOT NULL,
  `c_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`c_id`, `c_name`) VALUES
(1, 'Wedding Shoot'),
(2, 'Maternity Shoot'),
(3, 'Portrait Shoot'),
(4, 'Product Shoot'),
(5, 'Event Shoot');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `m_id` int(10) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`m_id`, `name`, `email`, `phone`, `message`) VALUES
(2, 'Pavan', 'pavanvalvi20@gmail.com', '8262977374', 'contact');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `p_id` int(10) NOT NULL,
  `p_name` varchar(50) NOT NULL,
  `p_desc` varchar(255) NOT NULL,
  `p_price` varchar(50) NOT NULL,
  `c_id` int(10) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`p_id`, `p_name`, `p_desc`, `p_price`, `c_id`, `image`, `details`) VALUES
(1, 'Pre-Wedding', 'A beautiful pre-wedding photoshoot package that captures romantic moments with stunning locations and professional photography.', '10000', 1, 'uploads/prewed.jpg', '📸 Professional Photographer – Expert photography service.\r\n🏞️ 2 Location Choices – Outdoor or studio-based photoshoots.\r\n👗 2 Outfit Changes – Capture different styles and moods.\r\n🖼️ 40 Edited Photos – High-resolution, professionally edited images.\r\n💻 Digital Delivery – Online gallery for easy access and sharing.'),
(2, 'Enchanted Moments: Pre-Wedding', 'A stunning wedding package that blends cinematic videography and professional photography to capture your love story in beautiful locations.', '18000', 1, 'uploads/prewedding2.jpg', '📸 Pro Photography – Capture every moment with clarity.\n🎥 Cinematic Videography – Short film of your love story.\n🏞️ 2 Location Choices – Scenic outdoor or studio settings.\n🖼️ 40 Edited Photos – High-quality, professionally edited images.\n🎞️ Edited Video – Cinematic video with background music.\n💒 Props & Setup – Creative props for unique frames.'),
(3, 'Wedding', 'A complete wedding package including professional photography and cinematic videography to capture your special moments beautifully.', '40000', 1, 'uploads/wed.jpg', '📸 Full-Day Photography – Complete wedding coverage.\r\n🎥 Cinematic Highlights – 3-5 minute wedding story with music.\r\n🖼️ 50 Edited Photos – Handpicked, high-quality images.\r\n💾 Personalized USB Drive – Digital album with photos & videos.\r\n💒 Creative Setups – Customized props and backgrounds.'),
(4, 'Luxury Wedding Package', 'Celebrate your big day with our Luxury Wedding Package - a perfect blend of elegant photography, cinematic videography, and breathtaking aerial shots, ensuring every precious moment is captured with timeless perfection.', '90000', 1, 'uploads/luxurywedding.jpg', '📸 Elite Photography – Premium shots with expert precision.\n🎥 Cinematic Videography – Captivating wedding film with music.\n🌆 4K Drone Coverage – Aerial views for a grand perspective.\n🖼️ 75 Edited Photos – Handpicked, high-quality images.\n🎞️ Teaser & Full Video – Short teaser + full wedding film.\n💾 Luxury USB Keepsake – Personalized drive with all memories.'),
(5, 'Pure Bliss: Maternity Memories', 'A heartwarming maternity shoot that beautifully captures the glow, love, and excitement of your journey to motherhood with a professionally styled indoor studio session.', '12000', 2, 'uploads/maternity2indoor.jpg', '🤰 Professional Photography – Elegant shots of mom-to-be.\n🏡 Indoor Studio Setup – Cozy, well-lit environment.\n👗 Wardrobe Assistance – Outfit guidance for perfect looks.\n🌸 Creative Props – Themed props for personalized moments.\n🖼️ 30 Edited Photos – High-quality, timeless memories.'),
(6, 'Eternal Bloom:\nMaternity Photo & Film', 'A breathtaking outdoor maternity shoot that captures the beauty of motherhood in scenic locations, blending natural light and stunning landscapes.', '13000', 2, 'uploads/maternity3outdoor.jpg', '🌿 Scenic Outdoor Locations – Nature’s beauty as your backdrop.\n☀️ Natural Light Photography – Soft, glowing portraits.\n📸 Pro Photographer – Expertly captured candid moments.\n🌺 Seasonal Props – Elegant props for a personal touch.\n🖼️ 35 Edited Photos – Beautifully retouched memories.'),
(7, 'Eternal Bloom: Maternity Photo & Film', 'A premium maternity package that beautifully captures your journey to motherhood with professional photography and cinematic videography, ensuring every precious moment is preserved forever.', '18000', 2, 'uploads/maternity4.jpg', '🤰 Photography – Magical shots during sunrise or sunset.\r\n🎥 Mini Documentary – A short film with personal interviews.\r\n🌆 Drone Footage – Aerial views for stunning perspectives.\r\n🖼️ Custom Photo Album – A printed keepsake of cherished moments.\r\n🌸 Floral & Theme Setup – Personalized decor to match your vision.\r\n💾 Digital & USB Delivery – Easy access to all photos and videos.'),
(8, 'Timeless Traditions: Portrait Session', 'A classic portrait session capturing elegance and cultural heritage with traditional outfits and artistic photography.', '8000', 3, 'uploads/portrait1.jpg', '📸 Traditional Portraits – Elegant shots in cultural attire.\r\n🎨 Artistic Backdrops – Custom backgrounds for timeless appeal.\r\n👗 Wardrobe Guidance – Assistance in selecting traditional outfits.\r\n🖼️ 30 Edited Photos – High-quality, retouched images.\r\n🏛️ Heritage-Inspired Props – Props reflecting cultural essence.'),
(9, 'Runway Ready: Modeling Portraits', 'A professional modeling portrait session with creative lighting, stylish poses, and expert photography to enhance your portfolio.', '12000', 3, 'uploads/portrait2.jpg', '📸 Portfolio Shots – High-quality images for your portfolio.\r\n💡 Creative Lighting Techniques – Dramatic and flattering effects.\r\n🧍 Pose Guidance – Expert tips for confident, stylish poses.\r\n🖼️ 40 Edited Photos – Retouched images with a professional touch.\r\n🌆 Indoor & Outdoor Options – Choose studio or scenic locations.'),
(10, 'Together Forever: Couple Portraits', 'A romantic portrait session capturing the love and connection between couples through candid moments and beautifully styled photography.', '10000', 3, 'uploads/portrait3.jpg', '💞 Candid Couple Moments – Natural, unscripted interactions.\r\n🌆 Scenic or Studio Locations – Choose your perfect backdrop.\r\n📸 Professional Photography – Timeless shots of your bond.\r\n🖼️ 35 Edited Photos – Beautifully enhanced memories.\r\n💍 Themed Props Available – Personalized setups for unique shots.'),
(11, 'Product Perfection', 'A professional product photography session that showcases your product in the best light with high-quality, detailed shots.', '8000', 4, 'uploads/product1.jpg', '🎥 Cinematic Product Videos – Engaging visuals with creative angles.\n⚙️ Action Shots – Showcase product functionality in motion.\n🌆 Studio & Outdoor Options – Choose ideal shooting environments.\n🎞️ HD & 4K Resolution – High-quality video output.\n💾 Digital Delivery – Ready-to-use files for marketing.'),
(12, 'Product Motion', 'A dynamic product cinematography session capturing your product in action with creative videography and cinematic angles.', '10000', 4, 'uploads/product2.jpg', '📸 Professional Photography – High-quality product images.\n🎥 Cinematic Videography – Engaging product videos.\n💡 Creative Lighting Setup – Perfectly highlights product details.\n🌆 Indoor & Outdoor Shoots – Flexible location options.\n🎞️ HD & 4K Videos – Crisp, high-resolution visuals.\n💾 Digital Delivery – Photos and videos ready for marketing.'),
(13, 'Complete Product Showcase', 'A comprehensive product shoot with both stunning photography and cinematic videography, offering a complete visual presentation of your product.', '15000', 4, 'uploads/product4.mp4', '📸 High-Quality Shots – Crisp, detailed product images.\n💡 Creative Lighting Setup – Highlight product features perfectly.\n🛒 E-commerce Ready – Optimized images for online stores.\n🖼️ 40 Edited Photos – Retouched for a professional finish.\n🎨 Custom Backgrounds – Tailored settings to match your brand.'),
(14, 'Timeless Celebration: Birthday Shoot', 'A fun and vibrant birthday shoot capturing all the special moments, from cake cutting to candid laughter, in a lively atmosphere.', '10000', 5, 'uploads/event1.jpg', '🎂 Cake Cutting Moments – Timeless shots of the big moment.\n🎈 Colorful Decor & Props – Fun elements for lively photos.\n🤩 Candid Laughs Captured – Genuine, joyful interactions.\n🖼️ 30 Edited Photos – Bright, vibrant, and high-quality images.\n🎥 Event Highlights Video – A short film of the celebration.'),
(15, 'Engaged in Love: Engagement Shoot', 'A heartfelt engagement shoot to capture your love story with intimate moments, romantic poses, and stunning locations.', '12000', 5, 'uploads/event2.jpg', '💍 Romantic Portraits – Candid, heartfelt shots.\n🌅 Golden Hour Moments – Perfect sunset vibes.\n🌸 Custom Theme Setup – Personalized backdrops.\n🖼️ 40 Edited Photos – High-quality images.\n🎥 Mini Love Story Video – Cinematic highlights.'),
(16, 'Professional Impact: Corporate Event Shoot', 'A corporate event shoot that captures the essence of professionalism, team spirit, and key moments at your business gathering.', '15000', 5, 'uploads/event3.jpg', '🏢 Event Highlights – Capture key moments with a professional touch.\n🤝 Team Interactions – Candid teamwork moments.\n🎤 Speaker Focus – Clear shots of presentations and speeches.\n🖼️ 50 Edited Photos – High-quality, professionally edited images.\n🎥 Recap Video – A short, engaging event summary.'),
(17, 'Live in Motion: Concert Shoot', 'An electrifying concert shoot that captures the energy, stage presence, and crowd excitement through dynamic photography and videography.', '18000', 5, 'uploads/event4.jpg', '🎤 Live Performance Shots – Capture the energy of performers on stage.\n🔥 Crowd Reactions – Candid moments of audience excitement.\n💡 Stage Lighting Mastery – Dynamic shots with vibrant lighting effects.\n🎶 Behind-the-Scenes Coverage – Candid moments with artists and crew.\n🖼️ 60 Edited Photos – High-quality images showcasing event highlights.\n🎥 Concert Recap Video – A cinematic summary of the event\'s best moments.');

-- --------------------------------------------------------

--
-- Table structure for table `pimages`
--

CREATE TABLE `pimages` (
  `image_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pimages`
--

INSERT INTO `pimages` (`image_id`, `artist_id`, `image_path`) VALUES
(1, 1, 'uploads/rushi1.jpg'),
(2, 1, 'uploads/rushi2.jpg'),
(3, 1, 'uploads/rushi3.jpg'),
(4, 1, 'uploads/rushi4.jpg'),
(5, 1, 'uploads/rushi5.jpg'),
(6, 1, 'uploads/rushi6.jpg'),
(7, 1, 'uploads/rushi7.jpg'),
(8, 1, 'uploads/rushi8.jpg'),
(9, 1, 'uploads/rushi9.jpg'),
(10, 2, 'uploads/tejas1.jpg'),
(11, 2, 'uploads/tejas2.jpg'),
(12, 2, 'uploads/tejas3.jpg'),
(13, 2, 'uploads/tejas4.jpg'),
(14, 2, 'uploads/tejas5.jpg'),
(15, 2, 'uploads/tejas6.jpg'),
(16, 2, 'uploads/tejas7.jpg'),
(17, 2, 'uploads/tejas8.jpg'),
(18, 2, 'uploads/tejas9.jpg'),
(19, 3, 'uploads/trilo1.jpg'),
(20, 3, 'uploads/trilo2.jpg'),
(21, 3, 'uploads/trilo3.jpg'),
(22, 3, 'uploads/trilo4.jpg'),
(23, 3, 'uploads/trilo5.jpg'),
(24, 3, 'uploads/trilo6.jpg'),
(25, 3, 'uploads/trilo7.jpg'),
(26, 3, 'uploads/trilo8.jpg'),
(27, 3, 'uploads/trilo9.jpg'),
(28, 4, 'uploads/ani1.jpg'),
(29, 4, 'uploads/ani2.jpg'),
(30, 4, 'uploads/ani3.jpg'),
(31, 4, 'uploads/ani4.jpg'),
(32, 4, 'uploads/ani5.jpg'),
(33, 4, 'uploads/ani6.jpg'),
(34, 4, 'uploads/ani7.jpg'),
(35, 4, 'uploads/ani8.jpg'),
(36, 4, 'uploads/ani9.jpg'),
(37, 5, 'uploads/man1.jpg'),
(38, 5, 'uploads/man2.jpg'),
(39, 5, 'uploads/man3.jpg'),
(40, 5, 'uploads/man4.jpg'),
(41, 5, 'uploads/man5.jpg'),
(42, 5, 'uploads/man6.jpg'),
(43, 5, 'uploads/man7.jpg'),
(44, 5, 'uploads/man8.jpg'),
(45, 5, 'uploads/man9.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `portfolio`
--

CREATE TABLE `portfolio` (
  `portfolio_id` int(11) NOT NULL,
  `artist_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instagram_link` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `exp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `portfolio`
--

INSERT INTO `portfolio` (`portfolio_id`, `artist_id`, `name`, `description`, `instagram_link`, `image`, `role`, `exp`) VALUES
(1, 1, 'Rushi Shinde', 'A highly skilled wedding photographer with a keen eye for capturing magical moments. Specializes in candid and cinematic wedding photography.', 'https://www.instagram.com/rushi_shinde_photography_?igsh=aDB0bWs0M3J6d3Vk', 'uploads/rushishinde.jpg', 'Wedding Photographer', '6 years'),
(2, 2, 'Tejas Shelke', 'An experienced maternity and product photographer with over 8 years of expertise. Known for capturing the beauty of motherhood and creating stunning product visuals that stand out and also capturing beautiful Baby Portraits.', 'https://www.instagram.com/swaddled_stories?igsh=MWVleGI1NDNvMmp5aA==', 'uploads/tejas.jpg', 'Maternity & Baby Portraits Photographer', '8 years'),
(3, 3, 'Trilochan Bhawedi', 'A passionate fashion and portrait photographer with 5 years of experience. Expert in bringing out the personality and elegance of every subject, blending creativity with technical precision to craft visually stunning images.', 'https://www.instagram.com/trilochan_bhawedi?igsh=ajk3amJwMmQyZmg=', 'uploads/trilochan.jpg', 'Fashion & Portraits Photographer', '5 years'),
(4, 4, 'Animesh Kshtriya', 'A skilled cinematographer with over 8 years of experience in visual storytelling. Specializes in capturing cinematic moments with a keen eye for detail, lighting, and composition to bring stories to life.', 'https://www.instagram.com/animeshkshatriya?igsh=aTh6Y203bjBsOWgy', 'uploads/animesh.jpg', 'Cinematographer and Video Editor', '8 years'),
(5, 5, 'Mandodar Pardhi', 'A talented photographer and cinematographer with 6 years of experience. Passionate about capturing stunning visuals, from breathtaking photographs to cinematic storytelling, bringing creativity and technical mastery together.', 'https://www.instagram.com/practicing_cinematographer?igsh=YWw0YWxhNzcybnZ3', 'uploads/mandodar.jpg', 'Photographer & Cinematographer', '6 years');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone_no` varchar(10) NOT NULL,
  `password` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `phone_no`, `password`) VALUES
(1, 'Pavan Valvi', 'pavanvalvi20@gmail.com', '8262977374', '12345'),
(2, 'John Cena', 'johncena1234@gmail.com', '9785632154', '78945'),
(4, 'Pavan', 'pavanclicks2004@gmail.com', '8262977375', '12345');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`m_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `pimages`
--
ALTER TABLE `pimages`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD PRIMARY KEY (`portfolio_id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone_no` (`phone_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `c_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `m_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `p_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pimages`
--
ALTER TABLE `pimages`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `portfolio`
--
ALTER TABLE `portfolio`
  MODIFY `portfolio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`p_id`);

--
-- Constraints for table `packages`
--
ALTER TABLE `packages`
  ADD CONSTRAINT `packages_ibfk_1` FOREIGN KEY (`c_id`) REFERENCES `categories` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pimages`
--
ALTER TABLE `pimages`
  ADD CONSTRAINT `pimages_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `portfolio`
--
ALTER TABLE `portfolio`
  ADD CONSTRAINT `portfolio_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
