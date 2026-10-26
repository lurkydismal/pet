# pet

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/219b745055124bdd88fe1a9ecc6440d2)](https://app.codacy.com/gh/lurkydismal/pet/dashboard?utm_source=gh&utm_medium=referral&utm_content=&utm_campaign=Badge_grade)

> A PHP console application demonstrating object-oriented programming through a farm production simulation.

## Table of Contents

- [General Information](#general-information)
- [Technologies Used](#technologies-used)
- [Features](#features)
- [Setup](#setup)
- [Usage](#usage)
- [Project Status](#project-status)
- [Room for Improvement](#room-for-improvement)
- [License](#license)

## General Information

`pet` is a small command-line simulation of a farm. It models cows and chickens, simulates their production of milk and eggs over two seven-day periods, and reports both weekly output and cumulative totals.

The project is an exercise in object-oriented PHP. It uses interfaces, abstract base classes, inheritance, and polymorphism to represent animals and the products they generate.

## Technologies Used

- PHP (CLI; tested with PHP 8.4)

The project has no third-party package dependencies and does not require Composer.

## Features

- Models cows and chickens as different animal types.
- Generates 8–12 liters of milk per cow and 0–1 eggs per chicken on each production cycle.
- Manages animals through a `Farm` class, including adding or purchasing animals.
- Aggregates production by product type.
- Simulates two seven-day periods and reports the production difference for the second week.
- Tracks and displays cumulative production totals.
- Demonstrates interfaces, abstract classes, inheritance, and polymorphism.

## Setup

Install PHP CLI version 8.0 or newer, then clone the repository:

```bash
git clone https://github.com/lurkydismal/pet.git
cd pet
```

No dependency installation or build step is required.

## Usage

Run the application from the project directory:

```bash
php main.php
```

The program prints a short introduction in Russian, reports the number of animals on the farm, and displays milk and egg production for each simulated week and in total. Production quantities vary between runs because they are generated randomly.

## Project Status

Project is: _complete_.

## Room for Improvement

Possible extensions include:

- Add automated tests for animal production and farm aggregation.
- Separate the classes into individual files as the project grows.
- Validate animal types and requested quantities through a more explicit API.

## License

This project is open source and available under the [GNU General Public License v3.0](LICENSE).
