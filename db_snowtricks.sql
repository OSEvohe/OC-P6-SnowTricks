-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 13 nov. 2020 à 16:43
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_snowtricks`
--

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20201113133243', '2020-11-13 14:32:57', 548);

-- --------------------------------------------------------

--
-- Structure de la table `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `trick`
--

CREATE TABLE `trick` (
  `id` int(11) NOT NULL,
  `trick_group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cover_id` int(11) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick`
--

INSERT INTO `trick` (`id`, `trick_group_id`, `user_id`, `cover_id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 1, 'Le 180', 'le-180', 'un 180 désigne un demi-tour, soit 180 degrés d\'angle', '2020-11-13 15:22:58', '2020-11-13 15:22:58'),
(2, 1, 3, 3, 'Japan air', 'japan-air', 'Japan ou Japan air\r\n\r\nSaisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside.', '2020-11-13 15:28:41', '2020-11-13 15:32:30'),
(3, 1, 3, 5, 'Le Truck Driver', 'le-truck-driver', 'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)', '2020-11-13 15:40:14', '2020-11-13 15:40:14'),
(4, 3, 3, 7, '180 Backflip', '180-backflip', 'Rotation en arrière à 180°', '2020-11-13 15:50:33', '2020-11-13 15:50:33'),
(5, 1, 3, 9, 'Le stalefish', 'le-stalefish', 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière ;', '2020-11-13 15:55:56', '2020-11-13 15:55:56'),
(6, 1, 3, 11, 'Nose grab', 'nose-grab', 'Saisie de la partie avant de la planche, avec la main avant.', '2020-11-13 15:57:53', '2020-11-13 15:57:53'),
(7, 1, 3, 13, 'Seat belt', 'seat-belt', 'Saisie du carre frontside à l\'arrière avec la main avant', '2020-11-13 16:16:13', '2020-11-13 16:16:13'),
(8, 1, 3, 15, 'Tail Grab', 'tail-grab', 'Saisie de la partie arrière de la planche, avec la main arrière;', '2020-11-13 16:20:53', '2020-11-13 16:20:53'),
(9, 1, 3, 17, 'Indy', 'indy', 'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière', '2020-11-13 16:23:41', '2020-11-13 16:23:41'),
(10, 2, 3, 19, 'Le 720', 'le-720', 'Rotation de deux tours complets', '2020-11-13 16:25:53', '2020-11-13 16:25:53');

-- --------------------------------------------------------

--
-- Structure de la table `trick_contributors`
--

CREATE TABLE `trick_contributors` (
  `user_id` int(11) NOT NULL,
  `trick_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick_contributors`
--

INSERT INTO `trick_contributors` (`user_id`, `trick_id`) VALUES
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10);

-- --------------------------------------------------------

--
-- Structure de la table `trick_group`
--

CREATE TABLE `trick_group` (
  `id` int(11) NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick_group`
--

INSERT INTO `trick_group` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Grabs', 'grabs', '2020-11-13 15:16:21', '2020-11-13 15:16:21'),
(2, 'Rotations', 'rotations', '2020-11-13 15:16:40', '2020-11-13 15:16:40'),
(3, 'Flips', 'flips', '2020-11-13 15:16:48', '2020-11-13 15:16:48'),
(4, 'Slides', 'slides', '2020-11-13 15:17:02', '2020-11-13 15:17:02');

-- --------------------------------------------------------

--
-- Structure de la table `trick_media`
--

CREATE TABLE `trick_media` (
  `id` int(11) NOT NULL,
  `trick_id` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `trick_media`
--

INSERT INTO `trick_media` (`id`, `trick_id`, `type`, `content`, `alt`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Twist-180-5fae96c2d4d2f.jpeg', 'Twist 180', '2020-11-13 15:22:58', '2020-11-13 15:22:58'),
(2, 1, 2, 'https://www.youtube.com/watch?v=XyARvRQhGgk', 'How to Twist 180 on a Snowboard - Snowboarding Tricks', '2020-11-13 15:22:58', '2020-11-13 15:22:58'),
(3, 2, 1, 'Japan-Grab-5fae981926d2f.jpeg', 'Japan Grab', '2020-11-13 15:28:41', '2020-11-13 15:28:41'),
(4, 2, 2, 'https://www.youtube.com/watch?v=CzDjM7h_Fwo', 'How To Japan Grab - Snowboard Trick Tutorial', '2020-11-13 15:28:41', '2020-11-13 15:28:41'),
(5, 3, 1, 'truck-driver-1-5fae9aceaa2f4.jpeg', 'Le Truck Driver par Frederic Malard', '2020-11-13 15:40:14', '2020-11-13 15:40:14'),
(6, 3, 2, 'https://www.youtube.com/watch?v=Ey5elKTrUCk&ab_channel=SnowboardProCamp', '10 Snowboard Tricks To Learn Outside The Park', '2020-11-13 15:40:14', '2020-11-13 15:40:14'),
(7, 4, 1, 'backflip180-5fae9d39c82b3.jpeg', 'Backflip 180', '2020-11-13 15:50:33', '2020-11-13 15:50:33'),
(8, 4, 2, 'https://www.youtube.com/watch?v=arzLq-47QFA&t=128s&ab_channel=SnowboardProCamp', 'How To Layout a Backflip - Snowboarding Trick Tutorial', '2020-11-13 15:50:33', '2020-11-13 15:50:33'),
(9, 5, 1, 'maxresdefault-5fae9e7cc679a.jpeg', 'Stale Grab', '2020-11-13 15:55:56', '2020-11-13 15:55:56'),
(10, 5, 2, 'https://www.youtube.com/watch?v=f9FjhCt_w2U&ab_channel=SnowboarderMagazine', 'How to Grab Stalefish | TransWorld SNOWboarding Grab Directory', '2020-11-13 15:55:56', '2020-11-13 15:55:56'),
(11, 6, 1, 'Nosegrab-5fae9ef11ce0e.jpeg', 'Le Nosegrab', '2020-11-13 15:57:53', '2020-11-13 15:57:53'),
(12, 6, 2, 'https://www.youtube.com/watch?v=M-W7Pmo-YMY', 'How to Nose Grab Snowboard - Snowboarding Tricks', '2020-11-13 15:57:53', '2020-11-13 15:57:53'),
(13, 7, 1, 'seatbelt-alex-sherman-back-fenelon-1-5faea33d4a407.jpeg', 'Seat Belt', '2020-11-13 16:16:13', '2020-11-13 16:16:13'),
(14, 7, 2, 'https://www.youtube.com/watch?v=4vGEOYNGi_c&feature=emb_logo&ab_channel=SnowboarderMagazine', 'How to Grab Seatbelt | TransWorld SNOWboarding Grab Directory', '2020-11-13 16:16:13', '2020-11-13 16:16:13'),
(15, 8, 1, 'tailgrab-5faea455e64bb.jpeg', 'How to Tail Grab', '2020-11-13 16:20:53', '2020-11-13 16:20:53'),
(16, 8, 2, 'https://www.youtube.com/watch?v=YAElDqyD-3I&ab_channel=SnowboarderMagazine', 'How to Tail Grab | TransWorld SNOWboarding Grab Directory', '2020-11-13 16:20:53', '2020-11-13 16:20:53'),
(17, 9, 1, 'Indy-5faea4fd935b1.jpeg', 'Indy', '2020-11-13 16:23:41', '2020-11-13 16:23:41'),
(18, 9, 2, 'https://www.youtube.com/watch?v=6yA3XqjTh_w&ab_channel=SnowboardProCamp', 'How To Indy Grab - Snowboarding Tricks', '2020-11-13 16:23:41', '2020-11-13 16:23:41'),
(19, 10, 1, '720-5faea5814afd0.jpeg', 'Le 720', '2020-11-13 16:25:53', '2020-11-13 16:25:53'),
(20, 10, 2, 'https://www.youtube.com/watch?v=4JfBfQpG77o&ab_channel=SnowboardProCamp', '720 Snowboard Trick Progression with TJ', '2020-11-13 16:25:53', '2020-11-13 16:25:53');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `photo`, `display_name`, `created_at`, `updated_at`) VALUES
(3, 'Admin@snowtricks', '[\"ROLE_ADMIN\",\"ROLE_USER_VERIFIED\"]', '$argon2id$v=19$m=65536,t=4,p=1$SEVmLjlqbFN1RU8xT1M0Rg$wnU1z7wuUyvEJMyFUGvNqbz/5Lfmz+PnnxTT2fGvnb4', NULL, 'Admin', '2020-11-13 14:50:06', '2020-11-13 16:25:53');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CB281BE2E` (`trick_id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748AA76ED395` (`user_id`);

--
-- Index pour la table `trick`
--
ALTER TABLE `trick`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D8F0A91E5E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_D8F0A91E989D9B62` (`slug`),
  ADD UNIQUE KEY `UNIQ_D8F0A91E922726E9` (`cover_id`),
  ADD KEY `IDX_D8F0A91E9B875DF8` (`trick_group_id`),
  ADD KEY `IDX_D8F0A91EA76ED395` (`user_id`);

--
-- Index pour la table `trick_contributors`
--
ALTER TABLE `trick_contributors`
  ADD PRIMARY KEY (`user_id`,`trick_id`),
  ADD KEY `IDX_46328CF9A76ED395` (`user_id`),
  ADD KEY `IDX_46328CF9B281BE2E` (`trick_id`);

--
-- Index pour la table `trick_group`
--
ALTER TABLE `trick_group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A6EF447A5E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_A6EF447A989D9B62` (`slug`);

--
-- Index pour la table `trick_media`
--
ALTER TABLE `trick_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A103A1B3B281BE2E` (`trick_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D649D5499347` (`display_name`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `trick`
--
ALTER TABLE `trick`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `trick_group`
--
ALTER TABLE `trick_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `trick_media`
--
ALTER TABLE `trick_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_9474526CB281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);

--
-- Contraintes pour la table `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748AA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `trick`
--
ALTER TABLE `trick`
  ADD CONSTRAINT `FK_D8F0A91E922726E9` FOREIGN KEY (`cover_id`) REFERENCES `trick_media` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_D8F0A91E9B875DF8` FOREIGN KEY (`trick_group_id`) REFERENCES `trick_group` (`id`),
  ADD CONSTRAINT `FK_D8F0A91EA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `trick_contributors`
--
ALTER TABLE `trick_contributors`
  ADD CONSTRAINT `FK_46328CF9A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_46328CF9B281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `trick_media`
--
ALTER TABLE `trick_media`
  ADD CONSTRAINT `FK_A103A1B3B281BE2E` FOREIGN KEY (`trick_id`) REFERENCES `trick` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
