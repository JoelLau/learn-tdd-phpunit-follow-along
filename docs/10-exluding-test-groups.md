# Excluding Test Groups

1. Open phpunit.xml.dist
2. Add a new section under `</testsuites>` (just recommended location, not enforced)
3. Add a `<groups>` section
4. Inside the new section, add a `<exclude>` exclude section
5. Add a `<group>` entry with the contents set to "integration"

```.xml
...
</testsuites>

<groups>
    <exclude>
        <group>integration</group>
    </exclude>
</groups>
```

To run groups that have been exluded, use the `--group` flag:

```bash
symfony php bin/phpunit tests --group integration
```

(where `integration` is the excluded group)
