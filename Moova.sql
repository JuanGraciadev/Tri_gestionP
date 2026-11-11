-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: tri-gestionlt
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categoria` (
  `id_categoria` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id_categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente`
--

DROP TABLE IF EXISTS `cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente` (
  `id_cliente` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_cliente`),
  KEY `cliente_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `cliente_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente`
--

LOCK TABLES `cliente` WRITE;
/*!40000 ALTER TABLE `cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalle_venta`
--

DROP TABLE IF EXISTS `detalle_venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalle_venta` (
  `id_detalle_de_venta` bigint unsigned NOT NULL AUTO_INCREMENT,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `descuento` double DEFAULT NULL,
  `id_venta` bigint unsigned DEFAULT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `tipo_venta` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNIDAD',
  `id_presentacion` bigint unsigned DEFAULT NULL,
  `nombre_presentacion` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cantidad_pacas` int DEFAULT NULL,
  `unidades_por_paca` int NOT NULL DEFAULT '1',
  `id_producto` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_detalle_de_venta`),
  KEY `detalle_venta_id_venta_foreign` (`id_venta`),
  KEY `detalle_venta_id_producto_foreign` (`id_producto`),
  CONSTRAINT `detalle_venta_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE RESTRICT,
  CONSTRAINT `detalle_venta_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalle_venta`
--

LOCK TABLES `detalle_venta` WRITE;
/*!40000 ALTER TABLE `detalle_venta` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalle_venta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detalles`
--

DROP TABLE IF EXISTS `detalles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detalles` (
  `id_detalles` bigint unsigned NOT NULL AUTO_INCREMENT,
  `unidades` int DEFAULT NULL,
  `tipo_envase` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacidad` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `proveedor` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_lote` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_detalles`),
  KEY `detalles_id_lote_foreign` (`id_lote`),
  CONSTRAINT `detalles_id_lote_foreign` FOREIGN KEY (`id_lote`) REFERENCES `lote` (`id_lote`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detalles`
--

LOCK TABLES `detalles` WRITE;
/*!40000 ALTER TABLE `detalles` DISABLE KEYS */;
/*!40000 ALTER TABLE `detalles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devolucion_retornables`
--

DROP TABLE IF EXISTS `devolucion_retornables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devolucion_retornables` (
  `id_retornables` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cantidad` int DEFAULT NULL,
  `id_producto` bigint unsigned DEFAULT NULL,
  `id_usuario` bigint unsigned DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_retornables`),
  KEY `devolucion_retornables_id_producto_foreign` (`id_producto`),
  KEY `devolucion_retornables_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `devolucion_retornables_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE SET NULL,
  CONSTRAINT `devolucion_retornables_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devolucion_retornables`
--

LOCK TABLES `devolucion_retornables` WRITE;
/*!40000 ALTER TABLE `devolucion_retornables` DISABLE KEYS */;
/*!40000 ALTER TABLE `devolucion_retornables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario_materia_prima`
--

DROP TABLE IF EXISTS `inventario_materia_prima`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_materia_prima` (
  `id_inventario_materia` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ingreso` int DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `bodega` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_detalles` bigint unsigned DEFAULT NULL,
  `id_retornables` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_inventario_materia`),
  KEY `inventario_materia_prima_id_detalles_foreign` (`id_detalles`),
  KEY `inventario_materia_prima_id_retornables_foreign` (`id_retornables`),
  CONSTRAINT `inventario_materia_prima_id_detalles_foreign` FOREIGN KEY (`id_detalles`) REFERENCES `detalles` (`id_detalles`) ON DELETE SET NULL,
  CONSTRAINT `inventario_materia_prima_id_retornables_foreign` FOREIGN KEY (`id_retornables`) REFERENCES `devolucion_retornables` (`id_retornables`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario_materia_prima`
--

LOCK TABLES `inventario_materia_prima` WRITE;
/*!40000 ALTER TABLE `inventario_materia_prima` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario_materia_prima` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventario_productos`
--

DROP TABLE IF EXISTS `inventario_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventario_productos` (
  `id_inventario` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `bodega` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_produccion` bigint unsigned DEFAULT NULL,
  `id_producto` bigint unsigned DEFAULT NULL,
  `id_usuario` bigint unsigned DEFAULT NULL,
  `cantidad` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_inventario`),
  KEY `inventario_productos_id_produccion_foreign` (`id_produccion`),
  KEY `inventario_productos_id_producto_foreign` (`id_producto`),
  KEY `inventario_productos_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `inventario_productos_id_produccion_foreign` FOREIGN KEY (`id_produccion`) REFERENCES `produccion` (`id_produccion`) ON DELETE SET NULL,
  CONSTRAINT `inventario_productos_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE SET NULL,
  CONSTRAINT `inventario_productos_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventario_productos`
--

LOCK TABLES `inventario_productos` WRITE;
/*!40000 ALTER TABLE `inventario_productos` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventario_productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lote`
--

DROP TABLE IF EXISTS `lote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lote` (
  `id_lote` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo_lote` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_usuario` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_lote`),
  KEY `lote_id_usuario_foreign` (`id_usuario`),
  CONSTRAINT `lote_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lote`
--

LOCK TABLES `lote` WRITE;
/*!40000 ALTER TABLE `lote` DISABLE KEYS */;
/*!40000 ALTER TABLE `lote` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `materias_primas`
--

DROP TABLE IF EXISTS `materias_primas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `materias_primas` (
  `id_materia_prima` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidad_medida` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unidades',
  `stock` decimal(12,3) NOT NULL DEFAULT '0.000',
  `stock_minimo` decimal(12,3) NOT NULL DEFAULT '10.000',
  `costo_unitario` decimal(12,2) NOT NULL DEFAULT '0.00',
  `bodega` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bodega Principal',
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_materia_prima`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `materias_primas`
--

LOCK TABLES `materias_primas` WRITE;
/*!40000 ALTER TABLE `materias_primas` DISABLE KEYS */;
/*!40000 ALTER TABLE `materias_primas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'0001_01_01_000002_create_jobs_table',1),(3,'2026_08_07_175807_create_rol_table',1),(4,'2026_08_07_175918_create_categoria_table',1),(5,'2026_08_07_175948_create_usuarios_table',1),(6,'2026_08_07_180029_create_cliente_table',1),(7,'2026_08_07_180053_create_lote_table',1),(8,'2026_08_07_180120_create_producto_table',1),(9,'2026_08_07_180216_create_detalles_table',1),(10,'2026_08_07_180258_create_devolucion_retornables_table',1),(11,'2026_08_07_180335_create_inevntario_materia_prima_table',1),(12,'2026_08_07_180404_create_produccion_table',1),(13,'2026_08_07_180428_create_venta_table',1),(14,'2026_08_07_180506_create_inventario_productos_table',1),(15,'2026_08_07_180544_create_detalles_venta_table',1),(16,'2026_08_07_234756_create_sessions_table',1),(17,'2026_08_08_012940_add_remember_token_to_usuarios_table',1),(18,'2026_11_04_000001_create_materias_primas_table',1),(19,'2026_11_04_000002_create_presentaciones_producto_table',1),(20,'2026_11_04_000003_create_movimientos_inventario_table',1),(21,'2026_11_04_000004_create_solicitudes_devolucion_table',1),(22,'2026_11_04_000005_add_presentaciones_to_detalle_venta_table',1),(23,'2026_11_04_000006_add_modalidad_to_produccion_table',1),(24,'2026_11_04_000007_add_receta_base_to_producto_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `movimientos_inventario`
--

DROP TABLE IF EXISTS `movimientos_inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `movimientos_inventario` (
  `id_movimiento` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo_recurso` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_recurso` bigint unsigned NOT NULL,
  `nombre_recurso` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_movimiento` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` decimal(12,3) NOT NULL,
  `stock_anterior` decimal(12,3) NOT NULL DEFAULT '0.000',
  `stock_nuevo` decimal(12,3) NOT NULL DEFAULT '0.000',
  `origen` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia_id` bigint unsigned DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `id_usuario` bigint unsigned DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_movimiento`),
  KEY `movimientos_inventario_tipo_recurso_id_recurso_index` (`tipo_recurso`,`id_recurso`),
  KEY `movimientos_inventario_fecha_index` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `movimientos_inventario`
--

LOCK TABLES `movimientos_inventario` WRITE;
/*!40000 ALTER TABLE `movimientos_inventario` DISABLE KEYS */;
/*!40000 ALTER TABLE `movimientos_inventario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presentaciones_producto`
--

DROP TABLE IF EXISTS `presentaciones_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presentaciones_producto` (
  `id_presentacion` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_producto` bigint unsigned NOT NULL,
  `nombre` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unidades_por_paca` int NOT NULL DEFAULT '15',
  `precio_paca` decimal(12,2) NOT NULL DEFAULT '0.00',
  `materiales_por_unidad` json DEFAULT NULL,
  `materiales_por_paca` json DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_presentacion`),
  KEY `presentaciones_producto_id_producto_index` (`id_producto`),
  CONSTRAINT `presentaciones_producto_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presentaciones_producto`
--

LOCK TABLES `presentaciones_producto` WRITE;
/*!40000 ALTER TABLE `presentaciones_producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `presentaciones_producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produccion`
--

DROP TABLE IF EXISTS `produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `produccion` (
  `id_produccion` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lote_produccion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `modalidad` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNIDADES',
  `id_presentacion` bigint unsigned DEFAULT NULL,
  `nombre_presentacion` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cantidad_pacas` int DEFAULT NULL,
  `unidades_por_paca` int NOT NULL DEFAULT '1',
  `unidades_fabricadas` int DEFAULT NULL,
  `materiales_consumidos` json DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `id_usuario` bigint unsigned DEFAULT NULL,
  `id_producto` bigint unsigned DEFAULT NULL,
  `id_inventario_materia` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_produccion`),
  KEY `produccion_id_usuario_foreign` (`id_usuario`),
  KEY `produccion_id_producto_foreign` (`id_producto`),
  KEY `produccion_id_inventario_materia_foreign` (`id_inventario_materia`),
  CONSTRAINT `produccion_id_inventario_materia_foreign` FOREIGN KEY (`id_inventario_materia`) REFERENCES `inventario_materia_prima` (`id_inventario_materia`) ON DELETE SET NULL,
  CONSTRAINT `produccion_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE SET NULL,
  CONSTRAINT `produccion_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produccion`
--

LOCK TABLES `produccion` WRITE;
/*!40000 ALTER TABLE `produccion` DISABLE KEYS */;
/*!40000 ALTER TABLE `produccion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id_producto` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_usuario` bigint unsigned DEFAULT NULL,
  `id_categoria` bigint unsigned DEFAULT NULL,
  `estado` tinyint(1) NOT NULL DEFAULT '1',
  `retornable` tinyint(1) NOT NULL DEFAULT '0',
  `receta_base` json DEFAULT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `producto_id_usuario_foreign` (`id_usuario`),
  KEY `producto_id_categoria_foreign` (`id_categoria`),
  CONSTRAINT `producto_id_categoria_foreign` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE SET NULL,
  CONSTRAINT `producto_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id_rol` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'administrador'),(2,'trabajador'),(3,'cliente');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `solicitudes_devolucion`
--

DROP TABLE IF EXISTS `solicitudes_devolucion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `solicitudes_devolucion` (
  `id_solicitud` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint unsigned NOT NULL,
  `id_producto` bigint unsigned NOT NULL,
  `id_venta` bigint unsigned NOT NULL,
  `cantidad` int NOT NULL,
  `notas` text COLLATE utf8mb4_unicode_ci,
  `estado` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pendiente',
  `leida` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id_solicitud`),
  KEY `solicitudes_devolucion_id_usuario_foreign` (`id_usuario`),
  KEY `solicitudes_devolucion_id_producto_foreign` (`id_producto`),
  KEY `solicitudes_devolucion_id_venta_foreign` (`id_venta`),
  CONSTRAINT `solicitudes_devolucion_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE CASCADE,
  CONSTRAINT `solicitudes_devolucion_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE,
  CONSTRAINT `solicitudes_devolucion_id_venta_foreign` FOREIGN KEY (`id_venta`) REFERENCES `venta` (`id_venta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `solicitudes_devolucion`
--

LOCK TABLES `solicitudes_devolucion` WRITE;
/*!40000 ALTER TABLE `solicitudes_devolucion` DISABLE KEYS */;
/*!40000 ALTER TABLE `solicitudes_devolucion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_usuario` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombres` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `documento_numero` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_rol` bigint unsigned DEFAULT NULL,
  `estado` tinyint NOT NULL DEFAULT '1' COMMENT '1: activo, 0: inactivo',
  `intentos_fallidos` int NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `usuarios_id_rol_foreign` (`id_rol`),
  CONSTRAINT `usuarios_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `venta`
--

DROP TABLE IF EXISTS `venta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `venta` (
  `id_venta` bigint unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date DEFAULT NULL,
  `cantidad` int DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `estado` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_pago` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `id_cliente` bigint unsigned DEFAULT NULL,
  `id_usuario` bigint unsigned DEFAULT NULL,
  `id_producto` bigint unsigned DEFAULT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notas` text COLLATE utf8mb4_unicode_ci,
  `metodo_pago` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Efectivo',
  PRIMARY KEY (`id_venta`),
  KEY `venta_id_cliente_foreign` (`id_cliente`),
  KEY `venta_id_usuario_foreign` (`id_usuario`),
  KEY `venta_id_producto_foreign` (`id_producto`),
  CONSTRAINT `venta_id_cliente_foreign` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE SET NULL,
  CONSTRAINT `venta_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`) ON DELETE SET NULL,
  CONSTRAINT `venta_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `venta`
--

LOCK TABLES `venta` WRITE;
/*!40000 ALTER TABLE `venta` DISABLE KEYS */;
/*!40000 ALTER TABLE `venta` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-05  9:08:51
