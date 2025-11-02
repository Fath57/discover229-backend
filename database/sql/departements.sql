-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : ven. 06 sep. 2024 à 20:44
-- Version du serveur : 10.4.25-MariaDB
-- Version de PHP : 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `fct_dev_7`
--

-- --------------------------------------------------------

--
-- Structure de la table `departements`
--

CREATE TABLE `departements` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `departements`
--

INSERT INTO `departements` (`id`, `code`, `name`, `created_at`, `updated_at`) VALUES
('09fb4fff-e6b0-4883-b8fc-736e2373298d', 'MN', 'MONO', '2023-04-05 05:28:12', '2023-05-21 17:14:25'),
('2f53eda3-780c-4757-a68c-e6f90989d9f7', 'DG', 'DONGA', '2023-02-09 12:53:30', '2023-02-09 13:11:01'),
('31dd359a-fb83-4dd1-8eaf-2b5593f1504d', 'ZU', 'ZOU', '2023-02-24 08:49:14', '2023-02-24 08:49:14'),
('395e59a6-236c-4509-9cc0-f22108521867', 'CF', 'COUFFO', '2023-02-24 08:49:14', '2023-02-24 08:49:14'),
('4e88b471-4805-4b60-a95e-57992ec70b44', 'AT', 'ATACORA', '2023-04-05 05:28:10', '2023-04-05 05:28:10'),
('585f2cba-42e7-4440-a788-ff8c70702ed7', 'PL', 'PLATEAU', '2023-02-24 08:49:14', '2023-02-24 08:49:14'),
('79fdf3a2-982b-4805-b2bb-169b976126b9', 'BG', 'BORGOU', '2023-04-05 05:28:10', '2023-04-05 05:28:10'),
('9802e9de-f5db-4665-87bc-4563f1e666bd', 'AL', 'ALIBORI', '2023-02-24 08:49:14', '2023-02-24 08:49:14'),
('a47f1ff3-2b6e-4247-82dd-5e6349bfc3ce', 'OU', 'OUEME', '2023-02-24 08:49:14', '2023-02-24 08:49:14'),
('c3d0f078-b645-424b-a3fd-28017462cf9b', 'Lt', 'LITTORAL', '2023-02-09 12:53:21', '2023-02-09 13:11:08'),
('f9f48e0f-b409-4f5d-9009-8771492eedda', 'CL', 'COLLINES', '2023-02-24 08:49:14', '2023-02-24 08:49:14'),
('fc9cbaf4-76dd-43c6-b4de-ac4213b6914e', 'ATL', 'ATLANTIQUE', '2023-02-24 08:49:14', '2023-02-24 08:49:14');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
