CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_current_version ON products(current_version);

CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(payment_status);
CREATE INDEX idx_orders_purchase_date ON orders(purchase_date);

CREATE INDEX idx_order_items_product ON order_items(product_id);

CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_gateway ON payments(gateway);

CREATE INDEX idx_product_versions_current ON product_versions(product_id, is_current);

CREATE INDEX idx_access_user_product ON user_product_access(user_id, product_id);
CREATE INDEX idx_access_update_count ON user_product_access(update_count);

CREATE INDEX idx_download_logs_created_at ON download_logs(created_at);

CREATE INDEX idx_notifications_delivery_status ON update_notifications(delivery_status);
