<?php

namespace App;

class GildedRose
{
    public $name;
    public $quality;
    public $sellIn;

    public function __construct($name, $quality, $sellIn)
    {
        $this->name = $name;
        $this->quality = $quality;
        $this->sellIn = $sellIn;
    }

    public static function of($name, $quality, $sellIn) {
        return new static($name, $quality, $sellIn);
    }

    public function tick() {
        $category = ItemFactory::create($this);
        $category->tick();
    }
}

class ItemCategory {
    public function __construct(protected GildedRose $item) {}

    public function tick(): void {
        $this->updateSellIn();
        $this->updateQuality();
    }

    protected function updateSellIn(): void {
        $this->item->sellIn--;
    }

    protected function increaseQuality(int $amount = 1): void {
        $this->item->quality = min(50, $this->item->quality + $amount);
    }

    protected function decreaseQuality(int $amount = 1): void {
        $this->item->quality = max(0, $this->item->quality - $amount);
    }

    protected function updateQuality(): void {
        $degrade = ($this->item->sellIn < 0) ? 2 : 1;
        $this->decreaseQuality($degrade);
    }
}

class AgedBrie extends ItemCategory {
    protected function updateQuality(): void {
        $this->increaseQuality($this->item->sellIn < 0 ? 2 : 1);
    }
}

class Sulfuras extends ItemCategory {
    public function tick(): void {}
}

class BackstagePasses extends ItemCategory {
    protected function updateQuality(): void {
        if ($this->item->sellIn < 0) {
            $this->item->quality = 0;
            return;
        }

        $extra = 1;
        
        if ($this->item->sellIn < 5) {
            $extra = 3;
        } elseif ($this->item->sellIn < 10) {
            $extra = 2;
        }

        $this->increaseQuality($extra);
    }
}

class Conjured extends ItemCategory {
    protected function updateQuality(): void {
        $degrade = ($this->item->sellIn < 0) ? 4 : 2;
        $this->decreaseQuality($degrade);
    }
}

class ItemFactory {
    public static function create(GildedRose $item): ItemCategory {
        return match (true) {
            str_contains($item->name, 'Aged Brie') => new AgedBrie($item),
            str_contains($item->name, 'Sulfuras') => new Sulfuras($item),
            str_contains($item->name, 'Backstage passes') => new BackstagePasses($item),
            str_contains($item->name, 'Conjured') => new Conjured($item),
            default => new ItemCategory($item),
        };
    }
}