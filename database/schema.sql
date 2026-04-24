CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id SERIAL PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price NUMERIC(10, 2) NOT NULL,
    image_url VARCHAR(255),
    stock INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE orders (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    total NUMERIC(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE order_items (
    id SERIAL PRIMARY KEY,
    order_id INTEGER REFERENCES orders(id) ON DELETE CASCADE,
    product_id INTEGER REFERENCES products(id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL,
    price NUMERIC(10, 2) NOT NULL
);

-- Usuario administrador por defecto (password: admin123)
INSERT INTO users (name, email, password, role) VALUES 
('Administrador', 'admin@construtec.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Productos iniciales con las imágenes generadas
INSERT INTO products (name, description, price, image_url, stock) VALUES
('Taladro Inalámbrico 20V', 'Taladro percutor profesional con 2 baterías y cargador rápido. Ideal para trabajos pesados, diseño ergonómico de alta resistencia.', 125.50, 'assets/images/drill.png', 15),
('Martillo Ergonómico', 'Martillo de acero forjado con mango antideslizante para mejor agarre. Gran resistencia y durabilidad.', 18.75, 'assets/images/hammer.png', 40),
('Sierra Circular 1800W', 'Sierra circular de alta potencia para cortes precisos en madera. Incluye hoja de tungsteno.', 95.00, 'assets/images/circular_saw.png', 12),
('Set de Destornilladores', 'Juego de 12 destornilladores de precisión magnéticos. Diferentes tamaños y tipos de puntas para cualquier necesidad.', 24.99, 'assets/images/screwdriver.png', 30);
