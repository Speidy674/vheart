#!/usr/bin/env bash

clean_path() {
    local path=$1
    if [ -d "$path" ]; then
        # use git clean for directory
        # that way we only delete ignored files we dont care about (e.g. cache)
        echo "Cleaning $1..."
        git clean -qfdX "$path"
    elif [ -f "$path" ]; then
        # us rm for files
        echo "Removing $1..."
        rm -f "$path"
    fi
}

# Framework & Storage
clean_path "./bootstrap/cache"
clean_path "./storage/framework"
clean_path "./storage/logs"

# debugbar needs special treatment, potentially way too many files lol
find ./storage/debugbar -type f ! -name ".gitignore" -delete

# Filament
clean_path "./public/css/filament"
clean_path "./public/fonts/filament"
clean_path "./public/js/filament"

# Wayfinder
clean_path "./resources/js/actions"
clean_path "./resources/js/routes"
clean_path "./resources/js/wayfinder"

# Build & Misc Files
clean_path "./public/build"
clean_path "./frankenphp"
clean_path "./.phpstorm.meta.php"
clean_path "./_ide_helper.php"
clean_path "./public/hot"
clean_path "./public/frankenphp-worker.php"

echo "done"
