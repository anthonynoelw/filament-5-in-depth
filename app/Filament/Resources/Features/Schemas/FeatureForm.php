<?php

namespace App\Filament\Resources\Features\Schemas;

use App\Enums\Feature\FeatureStatus;
use App\Enums\FeatureType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('status')
                    ->options(FeatureStatus::class)
                    ->enum(FeatureStatus::class)
                    ->searchable()
                    ->required()
                    ->live()
                    ->default(FeatureStatus::Proposed->value),
                DatePicker::make('target_delivery_date')
                    ->rules([
                        function (Get $get){
                            return Rule::requiredIf($get('status') === FeatureStatus::Planned || $get('status') === FeatureStatus::InProgress);
                        }
                    ])
                    ->visibleJs(<<<'JS'
                        $get('status') === 'Planned' || $get('status') === 'In Progress'
                    JS),
                ToggleButtons::make('type')
                    ->options(FeatureType::class)
                    ->enum(FeatureType::class)
                    ->inline()
                    ->hiddenLabel()
                    ->required()
                    ->default(FeatureType::Feature->value),
                Slider::make('priority')
                    ->required()
                    ->minValue(1)
                    ->maxValue(10)
                    ->pips(PipsMode::Steps)
                    ->step(1)
                    ->fillTrack()
                    ->default(5),
                RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('effort_in_days')
                    ->required()
                    ->afterStateUpdatedJs(<<<'JS'
                        const isHighCost = $get('is_high_cost');
                        const effort = $state;
                        const costPerDay = isHighCost ? 1500 : 1000;
                        $set('cost', effort * costPerDay);
                    JS)
                    //->live()
                    //->afterStateUpdated(
                        //function ($state, Set $set, Get $get) {
                            //$set('cost', $get('is_high_cost') ? 1500 * $state : 1000 * $state);
                        //}
                    //)
                    ->numeric()
                    ->default(0),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('$'),
                Toggle::make('is_high_cost')
                    ->label('Is High Cost')
                    ->dehydrated(false)
                    ->afterStateUpdatedJs(<<<'JS'
                        const isHighCost = $state;
                        const effort = $get('effort_in_days');
                        const costPerDay = isHighCost ? 1500 : 1000;
                        $set('cost', effort * costPerDay);
                    JS)
                    // The old way of doing it
                    //->live()
                    //->afterStateUpdated( function ($state, Get $get, Set $set) {
                        //if ($state){
                            //$set('cost', $get('effort_in_days') * 1500);
                        //}
                        //else {
                            //$set('cost', $get('effort_in_days') * 1000);
                        //}
                    //})
                    ->default(false),
                    //DateTimePicker::make('delivered_at'),
            ]);
    }
}
