-- =====================================================
-- Base de datos NUEVA para el laboratorio EAV
-- Importar desde phpMyAdmin (pestaña "Importar") o pegar en la pestaña SQL
-- =====================================================
DROP DATABASE IF EXISTS db_eav_lab;
CREATE DATABASE db_eav_lab CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_eav_lab;

SET NAMES utf8mb4;

CREATE TABLE categorias (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE atributos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  id_categoria INT NOT NULL,
  FOREIGN KEY (id_categoria) REFERENCES categorias(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Nota: se usan producto_id / atributo_id porque así los nombra el script de inserción de la guía.
CREATE TABLE valores_productos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  producto_id INT NOT NULL,
  atributo_id INT NOT NULL,
  valor VARCHAR(150) NOT NULL,
  UNIQUE KEY uq_prod_attr (producto_id, atributo_id),
  FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE,
  FOREIGN KEY (atributo_id) REFERENCES atributos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===== Datos de la guía =====
INSERT INTO categorias VALUES (1, 'Ropa de Hombre'), (2, 'Teléfonos');
INSERT INTO atributos VALUES (1,'Marca'),(2,'Color'),(3,'Talla'),(4,'Año'),(5,'Sist. Operativo'),(6,'Almacenamiento');

INSERT INTO productos VALUES (1, 'Polo Casual', 1);
INSERT INTO valores_productos (producto_id, atributo_id, valor) VALUES
(1,1,'Lacoste'),(1,2,'Rojo'),(1,3,'L');

INSERT INTO productos VALUES (2, 'Smartphone X1', 2);
INSERT INTO valores_productos (producto_id, atributo_id, valor) VALUES
(2,1,'Samsung'),(2,4,'2025'),(2,5,'Android 15'),(2,6,'256GB'),(2,2,'Azul');

-- ===== Registros adicionales (>= 5 por tabla) =====
INSERT INTO categorias VALUES (3,'Laptops'),(4,'Calzado'),(5,'Televisores'),(6,'Libros'),(7,'Electrodomésticos');

INSERT INTO atributos VALUES (7,'Procesador'),(8,'RAM'),(9,'Pantalla'),(10,'Material'),(11,'Editorial');

INSERT INTO productos VALUES
(3,'Polo Deportivo',1),(4,'Camisa Formal',1),
(5,'Smartphone Y2',2),(6,'iPhone 15',2),
(7,'Laptop Pro 14',3),(8,'Laptop Air',3),
(9,'Zapatillas Run',4),(10,'Botines de Cuero',4),
(11,'Smart TV 50',5),(12,'Cien años de soledad',6),(13,'Licuadora Max',7);

INSERT INTO valores_productos (producto_id, atributo_id, valor) VALUES
(3,1,'Nike'),(3,2,'Azul'),(3,3,'M'),
(4,1,'Zara'),(4,2,'Blanco'),(4,3,'S'),
(5,1,'Xiaomi'),(5,4,'2024'),(5,5,'Android 14'),(5,6,'128GB'),(5,2,'Negro'),
(6,1,'Apple'),(6,4,'2023'),(6,5,'iOS 17'),(6,6,'128GB'),(6,2,'Negro'),
(7,1,'Lenovo'),(7,7,'Intel i7'),(7,8,'16GB'),(7,6,'512GB'),(7,9,'14"'),
(8,1,'HP'),(8,7,'Ryzen 5'),(8,8,'8GB'),(8,6,'256GB'),(8,9,'15.6"'),
(9,1,'Adidas'),(9,2,'Negro'),(9,3,'42'),(9,10,'Malla'),
(10,1,'Clarks'),(10,2,'Marrón'),(10,3,'41'),(10,10,'Cuero'),
(11,1,'LG'),(11,9,'50"'),(11,4,'2025'),(11,2,'Negro'),
(12,11,'Sudamericana'),(12,4,'1967'),
(13,1,'Oster'),(13,2,'Plomo'),(13,10,'Plástico');
