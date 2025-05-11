-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 11, 2025 alle 22:13
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `challengeme`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `accettare_sfide`
--

CREATE TABLE `accettare_sfide` (
  `usernameUtente` varchar(64) NOT NULL,
  `idSfida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `accettare_sfide`
--

INSERT INTO `accettare_sfide` (`usernameUtente`, `idSfida`) VALUES
('mario', 29),
('mirko', 9),
('paolo', 29),
('raffy', 29),
('raffy', 30),
('raffy', 31),
('samuel', 29);

-- --------------------------------------------------------

--
-- Struttura della tabella `gruppi`
--

CREATE TABLE `gruppi` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `data` date NOT NULL,
  `usernameUtenteCreatore` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `gruppi`
--

INSERT INTO `gruppi` (`id`, `nome`, `data`, `usernameUtenteCreatore`) VALUES
(1, 'Ciao', '0000-00-00', 'mirko'),
(2, 'Gruppo', '2025-05-10', 'mirko'),
(4, 'sdafsfda', '2025-05-11', 'mario');

-- --------------------------------------------------------

--
-- Struttura della tabella `inviti_gruppo`
--

CREATE TABLE `inviti_gruppo` (
  `idGruppo` int(11) NOT NULL,
  `usernameUtenteInvitato` varchar(64) NOT NULL,
  `usernameUtenteInvitante` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `inviti_gruppo`
--

INSERT INTO `inviti_gruppo` (`idGruppo`, `usernameUtenteInvitato`, `usernameUtenteInvitante`) VALUES
(4, 'mirko', 'mario'),
(4, 'samuel', 'mario');

-- --------------------------------------------------------

--
-- Struttura della tabella `messaggi`
--

CREATE TABLE `messaggi` (
  `id` int(11) NOT NULL,
  `testo` text NOT NULL,
  `data` date NOT NULL,
  `ora` time NOT NULL,
  `idGruppo` int(11) NOT NULL,
  `usernameUtente` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `messaggi`
--

INSERT INTO `messaggi` (`id`, `testo`, `data`, `ora`, `idGruppo`, `usernameUtente`) VALUES
(1, 'fdsfasdfad', '2025-05-10', '17:40:31', 1, 'mirko'),
(2, 'Ciao claudio', '2025-05-10', '18:00:08', 1, 'mirko'),
(3, 'fdsfasd', '2025-05-10', '18:48:06', 2, 'mirko'),
(4, 'Ciao sono mario', '2025-05-10', '18:54:16', 2, 'mario'),
(5, 'Ciao mirko', '2025-05-11', '20:50:23', 2, 'mario'),
(6, 'Hey', '2025-05-11', '20:50:35', 2, 'mario');

-- --------------------------------------------------------

--
-- Struttura della tabella `partecipazioni_gruppi`
--

CREATE TABLE `partecipazioni_gruppi` (
  `usernameUtente` varchar(64) NOT NULL,
  `idGruppo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `partecipazioni_gruppi`
--

INSERT INTO `partecipazioni_gruppi` (`usernameUtente`, `idGruppo`) VALUES
('mario', 2),
('mario', 4),
('mirko', 1),
('mirko', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `profili`
--

CREATE TABLE `profili` (
  `username` varchar(64) NOT NULL,
  `descrizione` text NOT NULL,
  `pathFotoProfilo` text NOT NULL,
  `punteggio` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `profili`
--

INSERT INTO `profili` (`username`, `descrizione`, `pathFotoProfilo`, `punteggio`) VALUES
('mario', '', 'Immagini/default.png', 100),
('mirko', 'Ciao belli!!', 'Immagini/Raptor.jpg', 2000),
('paolo', '', 'Immagini/default.png', 100),
('raffy', '', 'Immagini/default.png', 600),
('samuel', 'Ciao', 'Immagini/Raptor.jpg', 100);

-- --------------------------------------------------------

--
-- Struttura della tabella `seguire`
--

CREATE TABLE `seguire` (
  `usernameSeguito` varchar(64) NOT NULL,
  `usernameSeguente` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `seguire`
--

INSERT INTO `seguire` (`usernameSeguito`, `usernameSeguente`) VALUES
('mirko', 'mario'),
('mirko', 'raffy'),
('samuel', 'mario'),
('samuel', 'raffy');

-- --------------------------------------------------------

--
-- Struttura della tabella `sfide`
--

CREATE TABLE `sfide` (
  `id` int(11) NOT NULL,
  `descrizione` text NOT NULL,
  `dataInizio` date NOT NULL,
  `oraInizio` time NOT NULL,
  `dataFine` date NOT NULL,
  `oraFine` time NOT NULL,
  `pathFotoRicompensa` text NOT NULL,
  `punteggioRicompensa` int(11) NOT NULL,
  `usernameUtenteCreatore` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `sfide`
--

INSERT INTO `sfide` (`id`, `descrizione`, `dataInizio`, `oraInizio`, `dataFine`, `oraFine`, `pathFotoRicompensa`, `punteggioRicompensa`, `usernameUtenteCreatore`) VALUES
(9, 'Toccati il gomito', '2025-04-17', '00:00:00', '2025-04-30', '00:00:00', 'https://plus.unsplash.com/premium_photo-1745338250505-e1226b993ecc?q=80&w=2187&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D', 1000, 'mirko'),
(29, 'Saluta Luca', '2025-05-11', '11:22:00', '2025-05-12', '11:22:00', 'https://images.unsplash.com/photo-1517833971739-54eb158e481f?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3MzI2NzF8MHwxfHNlYXJjaHwxfHxTYWx1dGElMjBMdWNhfGVufDB8fHx8MTc0Njk1NTM4Mnww&ixlib=rb-4.1.0&q=80&w=1080', 100, 'mario'),
(30, 'abbraccia papà', '2025-05-11', '22:00:00', '2025-05-11', '23:59:00', 'https://images.unsplash.com/photo-1603786462825-f91dc68f094e?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3MzI2NzF8MHwxfHNlYXJjaHwxfHxhYmJyYWNjaWElMjBwYXAlQzMlQTB8ZW58MHx8fHwxNzQ2OTkzMzYyfDA&ixlib=rb-4.1.0&q=80&w=1080', 500, 'raffy'),
(31, 'Ciao', '2025-05-11', '23:00:00', '2025-05-12', '03:09:00', 'https://images.unsplash.com/photo-1601520525445-1039c1fa232b?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3MzI2NzF8MHwxfHNlYXJjaHwxfHxDaWFvfGVufDB8fHx8MTc0Njk5Mzg2OHww&ixlib=rb-4.1.0&q=80&w=1080', 22222, 'raffy');

-- --------------------------------------------------------

--
-- Struttura della tabella `traguardi`
--

CREATE TABLE `traguardi` (
  `usernameVincitore` varchar(64) NOT NULL,
  `idSfida` int(11) NOT NULL,
  `ora` time NOT NULL,
  `data` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `traguardi`
--

INSERT INTO `traguardi` (`usernameVincitore`, `idSfida`, `ora`, `data`) VALUES
('mario', 29, '11:23:37', '2025-05-11'),
('mirko', 9, '17:55:01', '2025-04-28'),
('paolo', 29, '21:14:50', '2025-05-11'),
('raffy', 29, '21:54:40', '2025-05-11'),
('raffy', 30, '21:56:13', '2025-05-11'),
('samuel', 29, '14:08:06', '2025-05-11');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `username` varchar(64) NOT NULL,
  `password` char(32) NOT NULL,
  `mail` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`username`, `password`, `mail`) VALUES
('mario', 'de2f15d014d40b93578d255e6221fd60', 'mario'),
('mirko', '13592f2caf86af30572a825229a2a8dc', 'mirko'),
('paolo', '969044ea4df948fb0392308cfff9cdce', 'paolo@gmail.com'),
('raffy', '78af6eb6c614955db6518002b2d6bfe8', 'raffy@gmail.com'),
('samuel', 'd8ae5776067290c4712fa454006c8ec6', 'samuel');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `accettare_sfide`
--
ALTER TABLE `accettare_sfide`
  ADD PRIMARY KEY (`usernameUtente`,`idSfida`),
  ADD KEY `idSfida` (`idSfida`);

--
-- Indici per le tabelle `gruppi`
--
ALTER TABLE `gruppi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usernameUtenteCreatore` (`usernameUtenteCreatore`);

--
-- Indici per le tabelle `inviti_gruppo`
--
ALTER TABLE `inviti_gruppo`
  ADD PRIMARY KEY (`idGruppo`,`usernameUtenteInvitato`),
  ADD KEY `usernameUtenteInvitante` (`usernameUtenteInvitante`),
  ADD KEY `usernameUtenteInvitato` (`usernameUtenteInvitato`);

--
-- Indici per le tabelle `messaggi`
--
ALTER TABLE `messaggi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usernameUtente` (`usernameUtente`),
  ADD KEY `idGruppo` (`idGruppo`);

--
-- Indici per le tabelle `partecipazioni_gruppi`
--
ALTER TABLE `partecipazioni_gruppi`
  ADD PRIMARY KEY (`usernameUtente`,`idGruppo`),
  ADD KEY `idGruppo` (`idGruppo`);

--
-- Indici per le tabelle `profili`
--
ALTER TABLE `profili`
  ADD PRIMARY KEY (`username`);

--
-- Indici per le tabelle `seguire`
--
ALTER TABLE `seguire`
  ADD PRIMARY KEY (`usernameSeguito`,`usernameSeguente`);

--
-- Indici per le tabelle `sfide`
--
ALTER TABLE `sfide`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usernameUtenteCreatore` (`usernameUtenteCreatore`);

--
-- Indici per le tabelle `traguardi`
--
ALTER TABLE `traguardi`
  ADD PRIMARY KEY (`usernameVincitore`,`idSfida`),
  ADD KEY `idSfida` (`idSfida`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `nome_constraint` (`mail`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `gruppi`
--
ALTER TABLE `gruppi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `messaggi`
--
ALTER TABLE `messaggi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `sfide`
--
ALTER TABLE `sfide`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `accettare_sfide`
--
ALTER TABLE `accettare_sfide`
  ADD CONSTRAINT `accettare_sfide_ibfk_1` FOREIGN KEY (`idSfida`) REFERENCES `sfide` (`id`),
  ADD CONSTRAINT `accettare_sfide_ibfk_2` FOREIGN KEY (`usernameUtente`) REFERENCES `utenti` (`username`);

--
-- Limiti per la tabella `gruppi`
--
ALTER TABLE `gruppi`
  ADD CONSTRAINT `gruppi_ibfk_1` FOREIGN KEY (`usernameUtenteCreatore`) REFERENCES `utenti` (`username`);

--
-- Limiti per la tabella `inviti_gruppo`
--
ALTER TABLE `inviti_gruppo`
  ADD CONSTRAINT `inviti_gruppo_ibfk_1` FOREIGN KEY (`idGruppo`) REFERENCES `gruppi` (`id`),
  ADD CONSTRAINT `inviti_gruppo_ibfk_2` FOREIGN KEY (`usernameUtenteInvitante`) REFERENCES `utenti` (`username`),
  ADD CONSTRAINT `inviti_gruppo_ibfk_3` FOREIGN KEY (`usernameUtenteInvitato`) REFERENCES `utenti` (`username`);

--
-- Limiti per la tabella `messaggi`
--
ALTER TABLE `messaggi`
  ADD CONSTRAINT `messaggi_ibfk_1` FOREIGN KEY (`usernameUtente`) REFERENCES `utenti` (`username`),
  ADD CONSTRAINT `messaggi_ibfk_2` FOREIGN KEY (`idGruppo`) REFERENCES `gruppi` (`id`);

--
-- Limiti per la tabella `partecipazioni_gruppi`
--
ALTER TABLE `partecipazioni_gruppi`
  ADD CONSTRAINT `partecipazioni_gruppi_ibfk_1` FOREIGN KEY (`idGruppo`) REFERENCES `gruppi` (`id`),
  ADD CONSTRAINT `partecipazioni_gruppi_ibfk_2` FOREIGN KEY (`usernameUtente`) REFERENCES `utenti` (`username`);

--
-- Limiti per la tabella `profili`
--
ALTER TABLE `profili`
  ADD CONSTRAINT `profili_ibfk_1` FOREIGN KEY (`username`) REFERENCES `utenti` (`username`);

--
-- Limiti per la tabella `sfide`
--
ALTER TABLE `sfide`
  ADD CONSTRAINT `sfide_ibfk_1` FOREIGN KEY (`usernameUtenteCreatore`) REFERENCES `utenti` (`username`);

--
-- Limiti per la tabella `traguardi`
--
ALTER TABLE `traguardi`
  ADD CONSTRAINT `traguardi_ibfk_1` FOREIGN KEY (`usernameVincitore`) REFERENCES `utenti` (`username`),
  ADD CONSTRAINT `traguardi_ibfk_2` FOREIGN KEY (`idSfida`) REFERENCES `sfide` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
