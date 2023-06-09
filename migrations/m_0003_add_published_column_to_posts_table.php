<?php

use App\Core\Migration;

class m_0003_add_published_column_to_posts_table extends Migration
{
    public function up() 
    {
        $this->execute("ALTER TABLE posts ADD published TINYINT NOT NULL DEFAULT 0 AFTER body");        
    }
 
    public function down() 
    {
        $this->execute("ALTER TABLE posts DROP COLUMN published");
    }
}