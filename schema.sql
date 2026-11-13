
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `shipping_automation`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adresler`
--

CREATE TABLE `adresler` (
  `id` int(11) NOT NULL,
  `adres_metni` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kargolar`
--

CREATE TABLE `kargolar` (
  `id` int(11) NOT NULL,
  `takip_no` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `musteri_id` int(11) NOT NULL,
  `personel_id` int(11) DEFAULT NULL,
  `gonderici_adi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gonderici_adres_id` int(11) NOT NULL,
  `alici_adi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alici_adres_id` int(11) NOT NULL,
  `teslimat_tarihi` date NOT NULL,
  `konum` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `durum` enum('Kabul edildi','Transferde','Dağıtımda','Teslim edildi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kabul edildi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kargo_hareketleri`
--

CREATE TABLE `kargo_hareketleri` (
  `id` int(11) NOT NULL,
  `kargo_id` int(11) NOT NULL,
  `tarih` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `durum` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `konum` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_unicode_ci,
  `kullanici_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL,
  `ad_soyad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eposta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sifre_hash` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('yonetici','personel','musteri') COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `adresler`
--
ALTER TABLE `adresler`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kargolar`
--
ALTER TABLE `kargolar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `takip_no` (`takip_no`),
  ADD KEY `musteri_id` (`musteri_id`),
  ADD KEY `personel_id` (`personel_id`),
  ADD KEY `gonderici_adres_id` (`gonderici_adres_id`),
  ADD KEY `alici_adres_id` (`alici_adres_id`);

--
-- Tablo için indeksler `kargo_hareketleri`
--
ALTER TABLE `kargo_hareketleri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kargo_id` (`kargo_id`),
  ADD KEY `kullanici_id` (`kullanici_id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `eposta` (`eposta`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `adresler`
--
ALTER TABLE `adresler`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kargolar`
--
ALTER TABLE `kargolar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kargo_hareketleri`
--
ALTER TABLE `kargo_hareketleri`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `kargolar`
--
ALTER TABLE `kargolar`
  ADD CONSTRAINT `kargolar_ibfk_1` FOREIGN KEY (`musteri_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kargolar_ibfk_2` FOREIGN KEY (`personel_id`) REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `kargolar_ibfk_3` FOREIGN KEY (`gonderici_adres_id`) REFERENCES `adresler` (`id`),
  ADD CONSTRAINT `kargolar_ibfk_4` FOREIGN KEY (`alici_adres_id`) REFERENCES `adresler` (`id`);

--
-- Tablo kısıtlamaları `kargo_hareketleri`
--
ALTER TABLE `kargo_hareketleri`
  ADD CONSTRAINT `kargo_hareketleri_ibfk_1` FOREIGN KEY (`kargo_id`) REFERENCES `kargolar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kargo_hareketleri_ibfk_2` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
