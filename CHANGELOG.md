# Liberator changelog

## 3.0.0 (2022-02-21)

- **[BC BREAK]** Dropped support for EOL PHP versions including:
    - `5.3`
    - `5.4`
    - `5.5`
    - `5.6`
    - `7.0`
    - `7.1`
    - `7.2`
    - `7.3`
- **[FIXED]** Fixed deprecation warnings under PHP `8.1`.

## 2.0.0 (2014-02-09)

- **[BC BREAK]** Some class members that were previously protected are now
  private. It is very unlikely that this affects anyone at all, but technically
  it's backwards incompatible.
- **[NEW]** Added an interface for identifying liberator proxied values.
- **[NEW]** API documentation
- **[MAINTENANCE]** Repository maintenance

## 1.1.1 (2013-03-04)

- **[NEW]** Added [Archer] integration
- **[MAINTENANCE]** Repository maintenance

[Archer]: https://github.com/IcecaveStudios/archer

## 1.1.0 (2012-08-02)

- **[IMPROVED]** Improved API

## 1.0.0 (2012-08-02)

- **[NEW]** Initial stable release
