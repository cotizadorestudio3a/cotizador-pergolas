#!/bin/bash

# Script de Testing para Cotizador Pérgolas
# Uso: ./run-tests.sh [opción]

echo "🧪 Cotizador Pérgolas - Testing Suite"
echo "====================================="

case "$1" in
    "unit")
        echo "📋 Ejecutando pruebas unitarias..."
        vendor/bin/pest tests/Unit/ --verbose
        ;;
    "feature")
        echo "🔗 Ejecutando pruebas de integración..."
        vendor/bin/pest tests/Feature/ --verbose
        ;;
    "quick")
        echo "⚡ Ejecutando pruebas rápidas..."
        vendor/bin/pest tests/Unit/
        ;;
    "all")
        echo "🚀 Ejecutando todas las pruebas..."
        vendor/bin/pest --verbose
        ;;
    "factory")
        echo "🏭 Ejecutando pruebas de factories..."
        vendor/bin/pest tests/Unit/PergolaFactoryTest.php tests/Unit/CuadriculaFactoryTest.php
        ;;
    "help"|*)
        echo "Opciones disponibles:"
        echo "  unit     - Ejecutar solo pruebas unitarias"
        echo "  feature  - Ejecutar solo pruebas de integración"
        echo "  quick    - Ejecutar pruebas rápidas (sin verbose)"
        echo "  all      - Ejecutar todas las pruebas"
        echo "  factory  - Ejecutar solo pruebas de factories"
        echo "  help     - Mostrar esta ayuda"
        echo ""
        echo "Ejemplo: ./run-tests.sh unit"
        ;;
esac
