### Настройка пакета

```yaml
    # packages/twig.yaml

    twig:
      default_path: '%kernel.project_dir%/templates'
      # ...
      paths:
        templates: ~
        vendor/maris/symfony-user-bundle/templates : ~
```