
## Настройка шаблонизатора twig. 
```yaml
    # packages/twig.yaml

    twig:
      default_path: '%kernel.project_dir%/templates'
      # ...
      paths:
        # Можно создать папку templates/user внутри проекта и хранить
        # там все переопределенные шаблоны.
        ### templates/user: ~
        
        # Сначала добавляем путь для поиска шаблона внутри проекта.
        # Чтобы любой шаблон пакета можно было переопределить.
        templates: ~
        
        # Добавляем путь для поиска шаблона в конец массива что-бы twig искал 
        # шаблон в плагине в последнюю очередь.
        vendor/maris/symfony-user-bundle/templates : ~
```